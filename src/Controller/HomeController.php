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
use App\Repository\SlidesRepository;
use Countable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;

class HomeController extends AbstractController implements Countable
{
    public function count(): int
    {
        return count($this);
    }
    /**
     * @Route("/", name="app_home")
     */
    public function index(ProduitRepository $produitRepository, SlidesRepository $slidesRepository, BlogRepository $blogRepository, CategorieRepository $categorieRepository): Response
    {


        return $this->render('home/index.html.twig', [
            "produits" => $produitRepository->findBy(["status" => true], ["createdAt" => "DESC"], 8),
            "categories" => $categorieRepository->findBy([], [], 8),
            "futur" => $produitRepository->findBy(["isFutur" => true, "status" => true]),
            'news' => $produitRepository->findBy(['nouveau' => true, "status" => true]),
            'best' => $produitRepository->findBy(['meilleur' => true, "status" => true]),
            'offre' => $produitRepository->findBy(['isOffre' => true, "status" => true]),
            'slide' => $slidesRepository->findBy([], [], 1),
            'slider' => $slidesRepository->findBy([], ["createdAt" => "DESC"], 1),
            'slides' => $slidesRepository->findBy([], [], 4),
            "blog" => $blogRepository->findBy([], [], 3)
        ]);
    }
    /**
     * @Route("/produit", name="app_produit", methods={"GET","POST"})
     */
    public function produit(Request $request, SearchProductRepository $searchProductRepository, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $limit = 9;
        $page = (int)$request->query->get("page", 1);
        $query = $request->query->get("search");

        $offest = ($page - 1) * $limit;
        $produits = $produitRepository->findBy(["status" => true], ["createdAt" => "DESC"], $limit, $offest);
        $counts = $produitRepository->count(["status" => true]);
        $search = new SearchProduct;
        $form = $this->createForm(SearchType::class, $search, ['method' => "GET"])->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $produits = $produitRepository->searchwithCate($search, $offest, $limit);
            $counts = $produitRepository->searchwithCatecount($search);
            foreach ($counts as  $value) {
                $count = $value;
                foreach ($count as $value) {
                    $counts = (int)$value;
                }
            }
        }

        return $this->render('home/produit.html.twig', [
            'categorie' => $query["Categorie"],
            'maxPrix' => $query["maxPrix"],
            'minPrix' => $query["minPrix"],
            'token' => $query["_token"],
            'produits' => $produits,
            'counts' => $counts,
            'page' => $page, 'limit' => $limit,
            'news' => $produitRepository->findBy(['nouveau' => true]),
            'best' => $produitRepository->findBy(['meilleur' => true]),
            'offre' => $produitRepository->findBy(['isOffre' => true]),
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
     * @Route("/produit/searchAjax", name="app_produit_search_ajax", methods={"GET"})
     */
    public function searchAjax(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, Request $request): Response
    {

        $result = (string) trim($request->query->get("nomPro"));
        $resulta = $produitRepository->searchAjax($result);
        foreach ($resulta as $r) {
            $nomPro[] = $r->getNomPro();
        }

        return $this->render("home/searchAjax.html.twig", [
            "resulta" => $nomPro
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
            'commentaire' => $commentaire->findBy(["produits" => $produit->getId(), "isPublier" => true], ["createdAt" => "DESC"], 2)

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
