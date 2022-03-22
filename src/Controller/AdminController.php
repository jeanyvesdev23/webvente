<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin",methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findBy([], ["createdAt" => "DESC"]);

        return $this->render('admin/index.html.twig', compact('produits'));
    }

    /**
     * @Route("/admin/produit/{id<[0-9]+>}", name="app_show_produit",methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('admin/show.html.twig', compact('produit'));
    }

    /**
     * @Route("/admin/produit/create", name="app_create_produit",methods={"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $produit = new Produit;
        $form = $this->createForm(ProduitType::class, $produit,);
        $form->handleRequest($request); //Gerer le requete de formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produit);
            $em->flush();

            $this->addFlash("succes", 'vous avez creé un produit avec succes');

            return $this->redirectToRoute("app_admin");
        }
        return $this->render('admin/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/produit/{id<[0-9]+>}/edit", name="app_edit_produit",methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);


        $form->handleRequest($request); //Gerer le requete de formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash("succes", 'vous avez editer un produit avec succes');

            return $this->redirectToRoute("app_admin");
        }
        return $this->render('admin/edit.html.twig', [
            "produit" => $produit,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/produit/{id<[0-9]+>}/delete", name="app_delete_produit",methods={"GET"})
     */
    public function delete(Produit $produit, EntityManagerInterface $em): Response
    {
        $em->remove($produit);
        $em->flush();

        $this->addFlash("info", 'vous avez suprimer un produit avec succes');
        return $this->redirectToRoute("app_admin");
    }
}
