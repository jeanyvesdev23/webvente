<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use App\Repository\MarqueRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $produits = $produitRepository->findAll();
        $categories = $categorieRepository->findAll();
        return $this->render('home/index.html.twig', compact("produits", "categories"));
    }
    /**
     * @Route("/produit", name="app_produit", methods={"GET"})
     */
    public function produit(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, MarqueRepository $marqueRepository): Response
    {
        $produits = $produitRepository->findAll();
        $categories = $categorieRepository->findAll();
        return $this->render('home/produit.html.twig', [
            'produits' => $produits,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/produit/{id<[0-9]+>}/show", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit, CategorieRepository $categorieRepository, MarqueRepository $marqueRepository): Response
    {
        $categorie = $categorieRepository->find($produit->getCategorie()->getId());
        $marque = $marqueRepository->find($produit->getCategorie()->getId());
        return $this->render('home/show.html.twig', [
            'produit' => $produit,
            'categorie' => $categorie,
            'marque' => $marque
        ]);
    }
}
