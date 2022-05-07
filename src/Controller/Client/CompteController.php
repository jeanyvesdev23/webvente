<?php

namespace App\Controller\Client;

use Countable;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Commande;
use App\Entity\Wishlist;
use App\Form\AddresType;
use App\Services\Cartservices;
use App\Repository\PanierRepository;
use App\Repository\CommandeRepository;
use App\Repository\WishlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use symfony\Component\HttpFoundation\File\UploadedFile;
use symfony\Component\HttpFoundation\File\Exception\FileException;

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

        return $this->render('compte/index.html.twig', [
            "users" => $users,
            'commandeTotal' => $commandeRepository->count(["users" => $users->getId()])
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
    public function favoriteProduit(Cartservices $favorite, WishlistRepository $wishlistRepository)
    {

        return $this->render("compte/favoriteproduit.html.twig", [
            "fav_produit" => $favorite->getfavorite()
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
