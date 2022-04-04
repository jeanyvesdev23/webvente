<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


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
   /**
    * @Route("/create",name="app_create")
    */
   public function create(Request $request, EntityManagerInterface $em, SluggerInterface $sluggerInterface)
   {
      $product = new Product;
      $form = $this->createForm(ProductType::class, $product);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
         $image = $form->get("imageProduct")->getData();
         if ($image) {
            $originalFileName = pathinfo($image->getClientOriginalName(), 1);
            $saveFile = $sluggerInterface->slug($originalFileName);
            $newFile = $saveFile . '_' . uniqid() . '.' . $image->guessExtension();
            try {
               $image->move(
                  $this->getParameter('images_directory'),
                  $newFile

               );
            } catch (\Throwable $th) {
               //throw $th;
            }
         }
         $product->setImageProduct($newFile);
         $em->persist($product);
         $em->flush();
         dd($product);
         return $this->redirectToRoute("app_produit");
      }
      return $this->render("produit/cretate.html.twig", [
         "form" => $form->createView()
      ]);
   }
}
