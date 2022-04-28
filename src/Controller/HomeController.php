<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Produit;
use App\Form\CommentaireType;
use App\Repository\BlogRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ProduitRepository $produitRepository, BlogRepository $blogRepository, CategorieRepository $categorieRepository): Response
    {
        $produits = $produitRepository->findAll();
        $categories = $categorieRepository->findAll();
        $blog = $blogRepository->findBy([], [], 4);
        return $this->render('home/index.html.twig', compact("produits", "categories", "blog"));
    }
    /**
     * @Route("/produit", name="app_produit", methods={"GET"})
     */
    public function produit(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('home/produit.html.twig', [
            'produits' => $produitRepository->findAll(),
            'categories' => $categorieRepository->findAll()

        ]);
    }
    /**
     * @Route("/produit/seach", name="app_produit_search", methods={"GET"})
     */
    public function search(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, Request $request): Response
    {

        $result = $request->query->get("nomPro");
        $result1 = $request->query->get("categorie");

        $resulta = $produitRepository->search($result, $result1);
        if ($resulta == null) {
            return $this->redirectToRoute("app_produit");
        }


        return $this->render('home/search.html.twig', [
            'produits' => $resulta,
            'categories' => $categorieRepository->findAll()
        ]);
    }

    /**
     * @Route("/produit/{id<[0-9]+>}/show", name="app_produit_show", methods={"GET","POST"})
     */
    public function show(Produit $produit, Request $request, CommentaireRepository $commentaire): Response
    {
        $commenter = new Commentaire;
        $form = $this->createForm(CommentaireType::class, $commenter)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commenter->setProduits($produit)->setUsers($this->getUser())->setIsPublier(true);

            $commentaire->add($commenter);
            return $this->redirectToRoute("app_produit_show", [
                "id" => $produit->getId()
            ]);
        }

        return $this->render('home/show.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
            "counts" => $commentaire->count(["produits" => $produit->getId()]),
            'commentaire' => $commentaire->findBy(["produits" => $produit->getId()], ["createdAt" => "DESC"], 2)

        ]);
    }
    /**
     * @Route("/produit/categorie/{id<[0-9]+>}", name="app_categorie_list", methods={"GET"})
     */
    public function categorielist(Categorie $categorie): Response
    {

        return $this->render('home/categorie_list.html.twig', [
            "categorie" => $categorie,
            'produits' => $categorie->getProduit()->getValues()

        ]);
    }
}
