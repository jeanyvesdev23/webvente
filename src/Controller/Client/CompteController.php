<?php

namespace App\Controller\Client;

use App\Entity\Commande;
use App\Entity\User;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use App\Services\Cartservices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
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
            "commandes" => $commande,
            "panier" => $panier->findBy(["commande" => $id])
        ]);
    }
    /**
     * @Route("/compte/favorite/produit",name="app_favorite_produit")
     */
    public function favoriteProduit(Cartservices $favorite)
    {

        return $this->render("compte/favoriteproduit.html.twig", [
            "fav_produit" => $favorite->getfavorite()
        ]);
    }
    /**
     * @Route("/compte/edit/{id}",name="app_compte_edit")
     */
    public function editCompte(User $user)
    {

        return $this->render("compte/favoriteproduit.html.twig", [
            "fav_produit" => $favorite->getfavorite()
        ]);
    }
}
