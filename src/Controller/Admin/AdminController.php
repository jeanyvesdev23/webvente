<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Form\CommandeType;
use App\Repository\CategorieRepository;
use App\Repository\PanierRepository;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use App\Repository\MarqueRepository;
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
     * @Route("/commandes",name="app_commandes")
     */
    public function commandes(CommandeRepository $commandeRepository)
    {

        return $this->render('admin/commandes.html.twig', [
            "commandes" => $commandeRepository->findBy([], ["createdAt" => "DESC"])
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
    public function ispublier(Produit $produit, EntityManagerInterface $em)
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
}
