<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('admin/index.html.twig', compact('produits'));
    }
    /**
     * @Route("/admin/produit/{id<[0-9]+>}", name="app_show_produit")
     */
    public function show(Produit $produit): Response
    {
        return $this->render('admin/showProduit.html.twig', compact('produit'));
    }
    /**
     * @Route("/admin/add_produit", name="app_add_produit")
     */
    public function addProduit(): Response
    {
        return $this->render('admin/addProduit.html.twig');
    }
}
