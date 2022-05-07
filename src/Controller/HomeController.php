<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Produit;
use App\Entity\SearchProduct;
use App\Form\CommentaireType;
use App\Form\SearchType;
use App\Repository\BlogRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommentaireRepository;
use App\Repository\SearchProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ProduitRepository $produitRepository, BlogRepository $blogRepository, CategorieRepository $categorieRepository): Response
    {


        return $this->render('home/index.html.twig', [
            "produits" => $produitRepository->findBy([], ["createdAt" => "DESC"], 8),
            "categories" => $categorieRepository->findAll(),
            "futur" => $produitRepository->findBy(["isFutur" => true]),
            'news' => $produitRepository->findBy(['nouveau' => true]),
            'best' => $produitRepository->findBy(['meilleur' => true]),
            'offre' => $produitRepository->findBy(['isOffre' => true]),
            "blog" => $blogRepository->findBy([], [], 4)
        ]);
    }
    /**
     * @Route("/produit", name="app_produit", methods={"GET","POST"})
     */
    public function produit(Request $request, SearchProductRepository $searchProductRepository, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $produits = $produitRepository->findAll();
        $search = new SearchProduct;
        $form = $this->createForm(SearchType::class, $search)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $produits = $produitRepository->searchwithCate($search);
        }
        return $this->render('home/produit.html.twig', [
            'produits' => $produits,
            'news' => $produitRepository->findBy(['nouveau' => true]),
            'best' => $produitRepository->findBy(['meilleur' => true]),
            'offre' => $produitRepository->findBy(['isOffre' => true]),
            'categories' => $categorieRepository->findAll(),
            "form" => $form->createView()

        ]);
    }
    /**
     * @Route("/produit/search", name="app_produit_search", methods={"GET"})
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
