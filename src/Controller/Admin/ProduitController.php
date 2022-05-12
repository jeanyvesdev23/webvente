<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\PromotionType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommentaireRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use symfony\Component\HttpFoundation\File\UploadedFile;
use symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("admin/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("", name="app_produit_index", methods={"GET","POST"})
     */
    public function index(Request $request, ProduitRepository $produitRepository): Response
    {
        $limit = 10;
        $page = (int)$request->query->get("page", 1);
        $offest = ($page - 1) * $limit;
        $searchPro = $request->request->get("searchPro");
        if ($searchPro == "") {
            # code...
            $produits = $produitRepository->findBy([], ["createdAt" => "DESC"], $limit, $offest);
        } else {

            $result = $produitRepository->searchPro($searchPro);
            if ($result == null) {
                $produits = $produitRepository->findBy([], ["createdAt" => "DESC"], $limit, $offest);
            } else {
                $produits = $result;
            }
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'counts' => $produitRepository->count([]),
            'page' => $page,
            'limit' => $limit,
            'offest' => $offest
        ]);
    }
    /**
     * @Route("/commentaire", name="app_commentaire_produit", methods={"GET", "POST"})
     */
    public function commentaire(Request $request, CommentaireRepository $commentaireRepository): Response
    {
        $limit = 6;
        $page = $request->query->get("page", 1);
        $offest = ($page - 1) * $limit;
        $commentaires = $commentaireRepository->findBy([], ["createdAt" => "DESC"], $limit, $offest);
        $counts = $commentaireRepository->count([]);
        $search = $request->request->get("search");

        if ($search == "") {
            $commentaires;
        } else {

            $result = $commentaireRepository->searchComWithPro($search, $offest, $limit);

            if ($result == null) {
                $commentaires;
            } else {
                $commentaires = $result;
                $counts = $commentaireRepository->countComWithPro($search, $offest, $limit);
                foreach ($counts as $value) {
                    $count = $value;
                    foreach ($count as $value) {
                        $counts = (int)$value;
                    }
                }
            }
        }

        return $this->render('produit/commentaire.html.twig', [
            "commentaires" => $commentaires,
            "counts" => $counts, "page" => $page, "limit" => $limit, "offest" => $offest
        ]);
    }

    /**
     * @Route("/new", name="app_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProduitRepository $produitRepository, SluggerInterface $sluggerInterface): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("imagePro")->getData();
            if ($image) {
                $origineFile = pathinfo($image->getClientOriginalName(), 1);
                $slugerFile = $sluggerInterface->slug($origineFile);
                $newFile = $slugerFile . '' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move($this->getParameter("images_directory"), $newFile);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            $produit->setImagePro($newFile);

            $produitRepository->add($produit);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_produit_show2", methods={"GET"})
     */
    public function show(Produit $produit, PanierRepository $panierRepository, EntityManagerInterface $em, CommentaireRepository $commentaire): Response
    {
        $total = $panierRepository->totalVendu($produit->getNomPro());
        foreach ($total as   $value) {
            $key = $value;
            foreach ($key as $nbtotal) {
                $total = $nbtotal;
            }
        }
        $totalVendu = (int)$total;

        $stock = $panierRepository->StockenCours($produit->getNomPro());
        foreach ($stock as   $value) {
            $key = $value;
            foreach ($key as $nbstock) {
                $stock = $nbstock;
            }
        }
        $proStock = $produit->setStock($produit->getStock() - (int)$stock);
        if ($proStock->getStock() < 0) {
            $proStock = $proStock->setStock(0);
        }
        $em->flush();

        return $this->render('produit/show.html.twig', [
            'totalVendu' => $totalVendu,
            'produit' => $produit,
            "countComm" => $commentaire->count(["produits" => $produit->getId()])
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, SluggerInterface $sluggerInterface, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("imagePro")->getData();
            if ($image) {
                $origineFile = pathinfo($image->getClientOriginalName(), 1);
                $slugerFile = $sluggerInterface->slug($origineFile);
                $newFile = $slugerFile . '' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move($this->getParameter("images_directory"), $newFile);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                $produit->setImagePro($newFile);
            }
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/promotion/{id}", name="app_promotion", methods={"GET", "POST"})
     */
    public function promotion(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(PromotionType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit->setIsOffre(true)->setIsFutur(false)->setNouveau(false);
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/promotion.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/{id}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
