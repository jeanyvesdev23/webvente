<?php

namespace App\Controller\Client;

use Countable;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Commande;
use App\Form\AddresType;
use App\Form\ChangePasswordType;
use App\Repository\PanierRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use symfony\Component\HttpFoundation\File\UploadedFile;
use symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CompteController extends AbstractController implements Countable
{
    public function count(): int
    {
        return $this->count();
    }
    /**
     * @Route("/compte", name="app_compte")
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        $users = $this->getUser();
        if (!$users) {
            return $this->redirectToRoute("app_login");
        }
        $subTotal = $commandeRepository->getSubtotal($users->getId());
        foreach ($subTotal as  $value) {
            $subTotal = $value;
            foreach ($subTotal as $value) {
                $subTotal = $value;
            }
        }
        (int) $subTotal;
        $quantity = $commandeRepository->getQuantity($users->getId());
        foreach ($quantity as  $value) {
            $quantity = $value;
            foreach ($quantity as $value) {
                $quantity = $value;
            }
        }
        (int) $quantity;

        return $this->render('compte/index.html.twig', [
            "users" => $users,
            'commandeTotal' => $commandeRepository->count(["users" => $users->getId()]),
            'subTotal' => $subTotal,
            'quantity' => $quantity
        ]);
    }

    /**
     * @Route("/compte/commandes",name="app_compte_commandes")
     */
    public function mesCommandes(CommandeRepository $commandeRepository)
    {
        $users = $this->getUser();


        return $this->render("compte/mescommandes.html.twig", [
            "commandes" => $commandeRepository->findBy(["users" => $users->getId()], ["createdAt" => "DESC"])
        ]);
    }
    /**
     * @Route("/compte/commandes/{id}",name="app_compte_commandes_show")
     */
    public function commandeShow(Commande $commande, $id, PanierRepository $panier)
    {

        return $this->render("compte/commande_show.html.twig", [
            "users" => $this->getUser(),
            "commande" => $commande,
            "panier" => $panier->findBy(["commande" => $id])
        ]);
    }
    /**
     * @Route("/compte/favorite/produit",name="app_favorite_produit")
     */
    public function favoriteProduit(ProduitRepository $produits)
    {


        return $this->render("compte/favoriteproduit.html.twig", [
            "fav_produit" => $this->getUser()->getWishLists()->getValues()
        ]);
    }
    /**
     * @Route("/compte/reception",name="app_compte_reception")
     */
    public function boiteReception(CommandeRepository $commandeRepository, PanierRepository $panierRepository)
    {
        $users = $this->getUser();

        $commande = $commandeRepository->getId();
        foreach ($commande as $value) {
            $commande = $value;
        }
        $panier = $panierRepository->findBy(["commande" => $commande["id"]]);
        foreach ($panier as $value) {
            $panier = $value;
        }


        return $this->render("compte/reception.html.twig", [
            "commandes" => $commandeRepository->getConfirme($users->getId())
        ]);
    }
    /**
     * @Route("/compte/reception/{id}",name="app_compte_reception_detail")
     */
    public function boiteReceptionDetail(Commande $commande, PanierRepository $panierRepository)
    {

        return $this->render("compte/receptionDetail.html.twig", [
            "commande" => $commande,
            "panier" => $panierRepository->findBy(["commande" => $commande->getId()])
        ]);
    }
    /**
     * @Route("/compte/changepassword",name="app_compte_changepassword")
     */
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $form->get("newPassword")->getData()));
            $em->flush();
            return $this->redirectToRoute("app_compte");
        }

        return $this->render("compte/passwordEdit.html.twig", [
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("/compte/edit/{id}",name="app_compte_edit")
     */
    public function editCompte(Request $request, EntityManagerInterface $em, SluggerInterface $sluggerInterface)
    {
        $users = $this->getUser();
        $form = $this->createForm(UserType::class, $users)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("imageUser")->getData();
            if ($image) {
                $origineFile = pathinfo($image->getClientOriginalName(), 1);
                $slugerFile = $sluggerInterface->slug($origineFile);
                $newFile = $slugerFile . '' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move($this->getParameter("images_directory"), $newFile);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                $users->setImageUser($newFile);
            }
            $em->flush();
            return $this->redirectToRoute("app_compte");
        }

        return $this->render("compte/compteEdit.html.twig", [
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("compte/addres/edit/{id}",name="app_addres_edit")
     */
    public function editAddres(Request $request, EntityManagerInterface $em)
    {
        $users = $this->getUser()->getAddres()->getValues();

        $form = $this->createForm(AddresType::class, $users[0])->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            return $this->redirectToRoute("app_compte");
        }

        return $this->render("compte/addresEdit.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
