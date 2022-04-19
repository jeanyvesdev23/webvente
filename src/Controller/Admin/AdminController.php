<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Form\StatusCommandeType;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
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
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', []);
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
    public function commandesDetail(Commande $commande, $id, PanierRepository $panier)
    {
        $form = $this->createForm(StatusCommandeType::class);
        return $this->render('admin/commandesDetail.html.twig', [
            "commandes" => $commande,
            "panier" => $panier->findBy(["commande" => $id]),
            "form" => $form->createView()
        ]);
    }
}
