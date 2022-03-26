<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="app_produit")
     */

    public function index(ProductRepository $prductRepository, CategoryRepository $categoryRepository): Response
    {
        $products = $prductRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('produit/index.html.twig', compact('products', 'categories'));
    }
}
