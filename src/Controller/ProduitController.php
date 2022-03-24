<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('produit/index.html.twig');
    }
    /**
     * @Route("/produit",name="app_produit")
     */

    public function produit(ProduitRepository $produitRepository)
    {
        $produits = $produitRepository->findAll();

        return $this->render('produit/produit.html.twig', compact('produits'));
    }
    /**
     * @Route("/cart",name="app_cart")
     */

    public function panier(ProduitRepository $produitRepository, Session $session)
    {
        $panier = $session->get('panier', []);
        $panierData = [];
        foreach ($panier as $id => $qte) {

            $panierData[] = array(
                "produit" => $produitRepository->find($id),
                "quantity" => $qte
            );
        }
        $total = 0;
        foreach ($panierData as $key) {
            $total += $key["produit"]->getPrix() * $key["quantity"];
        }
        $items = $panierData;
        $total = $total;

        return $this->render("produit/panier.html.twig", compact('items', 'total'));
    }

    /**
     * @Route("/add/produit/{id<[0-9]+>}",name="app_add_cart")
     */

    public function addPanier($id, Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute("app_produit");
    }
    /**
     * @Route("/remove/produit/{id<[0-9]+>}",name="app_remove_cart")
     */

    public function removePanier($id, Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute("app_cart");
    }
}
