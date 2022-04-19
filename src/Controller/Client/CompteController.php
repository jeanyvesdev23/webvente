<?php

namespace App\Controller\Client;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    /**
     * @Route("/compte", name="app_compte")
     */
    public function index(): Response
    {
        $users = $this->getUser();
        if (!$users) {
            return $this->redirectToRoute("app_login");
        }
        return $this->render('compte/index.html.twig', [
            "users" => $users
        ]);
    }
    /**
     * @Route("/compte/commandes",name="app_compte_commandes")
     */
    public function mesCommandes(CommandeRepository $commandeRepository)
    {
        return $this->render("compte/mescommandes.html.twig", [
            "commandes" => $commandeRepository->findBy([], ["createdAt" => "DESC"])
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
}
