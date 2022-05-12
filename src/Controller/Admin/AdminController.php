<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Commentaire;
use App\Entity\Contact;
use App\Form\CommandeType;
use App\Repository\UserRepository;
use App\Repository\ContactRepository;
use App\Repository\MarqueRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route(" ", name="app_admin")
     */
    public function index(ProduitRepository $produit, CategorieRepository $categorie, MarqueRepository $marque, CommandeRepository $commande, UserRepository $user): Response
    {

        $vendus = $commande->findBy(["statusPaiement" => 2]);

        $totaux = 0;
        for ($i = 0; $i < $commande->count(["statusPaiement" => 2]); $i++) {
            $total[] = $vendus[$i]->getSubTotal();
        }
        foreach ($total as $key => $value) {
            $totaux += $value;
        }

        return $this->render('admin/index.html.twig', [
            "produits" => $produit->count([]),
            "commandes" => $commande->count([]),
            "passer" => $commande->count(["statusCommandes" => 1]),
            "confirmer" => $commande->count(["statusCommandes" => 2]),
            "traiter" => $commande->count(["statusCommandes" => 4]),
            "livrer" => $commande->count(["statusCommandes" => 3]),
            "vendus" => $totaux,
            "users" => $user->count([]),
            "categorie" => $categorie->findBy([], [], 4),
            "categories" => $categorie->count([]),
            "marque" => $marque->findBy([], [], 5),
            "marques" => $marque->count([])
        ]);
    }
    /**
     * @Route("/commandes",name="app_commandes"methodes={"GET","POST"})
     */
    public function commandes(Request $request, CommandeRepository $commandeRepository)
    {
        $limit = 10;
        $page = $request->query->get("page", 1);
        $offest = ($page - 1) * $limit;
        $commandes = $commandeRepository->findBy([], ["createdAt" => "DESC"], $limit, $offest);
        $counts = $commandeRepository->count([]);
        $searchEl = $request->request->get("statusL");
        $searchEp = $request->request->get("statusP");
        $searchC = $request->request->get("code");
        if ($searchEl == "" || $searchEp == "" || $searchC == "") {
            $commandes;
        }

        return $this->render('admin/commandes.html.twig', [
            "commandes" => $commandes,
            "counts" => $counts, "page" => $page, "limit" => $limit
        ]);
    }
    /**
     * @Route("/commandes/{id}",name="app_commandes_detail")
     */
    public function commandesDetail(Commande $commande, $id, Request $request, EntityManagerInterface $em, PanierRepository $panier)
    {
        $form = $this->createForm(CommandeType::class, $commande)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $status = $form->get("statusCommandes")->getData();
            $ispaie = $form->get("statusPaiement")->getData();
            $commande->setStatusCommandes($status)->setStatusPaiement($ispaie);
            $em->persist($commande);
            $em->flush();
            return $this->redirectToRoute("app_commandes");
        }
        return $this->render('admin/commandesDetail.html.twig', [
            "commandes" => $commande,
            "panier" => $panier->findBy(["commande" => $id]),
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("/produit/isStatus/{id}",name="app_status")
     */
    public function ispublierpro(Produit $produit, EntityManagerInterface $em)
    {
        if ($produit->getStatus() == true) {
            $status = false;
        } else {
            $status = true;
        }
        $produit->setStatus($status);
        $em->flush();
        return $this->redirectToRoute("app_produit_index");
    }
    /**
     * @Route("/produit/commentaire/isStatus/{id}",name="app_status_com")
     */
    public function ispubliercom(Commentaire $commentaire, EntityManagerInterface $em)
    {
        if ($commentaire->getIsPublier() == true) {
            $status = false;
        } else {
            $status = true;
        }
        $commentaire->setIsPublier($status);
        $em->flush();
        return $this->redirectToRoute("app_commentaire_produit");
    }
    /**
     * @Route("/client",name="app_client")
     */
    public function client(UserRepository $user)
    {


        return $this->render("admin/client.html.twig", [
            "clients" => $user->findAll()
        ]);
    }
    /**
     * @Route("/client/detail/{id}",name="app_client_detail")
     */
    public function clientDetail(User $user, CommandeRepository $commande)
    {
        $vendus = $commande->findBy(["statusPaiement" => 2, "users" => $user->getId()]);
        $totaux = 0;
        if ($vendus) {

            for ($i = 0; $i < $commande->count(["statusPaiement" => 2, "users" => $user->getId()]); $i++) {
                $total[] = $vendus[$i]->getSubTotal();
            }
            foreach ($total as $key => $value) {
                $totaux += $value;
            }
        }

        return $this->render("admin/clientDetail.html.twig", [
            "client" => $user,
            "total" => $totaux

        ]);
    }
    /**
     * @Route("/contacts",name="app_contact_list")
     */
    public function contactList(ContactRepository $contact)
    {


        return $this->render("admin/contactList.html.twig", [
            "contacts" => $contact->findAll()

        ]);
    }
    /**
     * @Route("/contacts/{id}",name="app_contact_detail")
     */
    public function contactDetail(Contact $contact)
    {


        return $this->render("admin/contactDetail.html.twig", [
            "contacts" => $contact

        ]);
    }
}
