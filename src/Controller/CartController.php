<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Cartservices;

class CartController extends AbstractController
{
    private $cartservices;
    public function __construct(Cartservices $cartservices)
    {
        $this->cartservices = $cartservices;
    }
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(): Response
    {
        $carts = $this->cartservices->getFullCart();
        if (!isset($carts["produit"])) {
            return $this->redirectToRoute("app_home");
        }


        return $this->render('cart/index.html.twig', [
            "carts" => $carts['produit'],
            "data" => $carts["data"]
        ]);
    }
    /**
     * @Route("/addcart/{id<[0-9]+>}",name="add_cart")
     */
    public function addCart($id)
    {
        $this->cartservices->addCart($id);

        return $this->redirectToRoute("app_cart");
    }
    /**
     * @Route("/deletecart/{id<[0-9]+>}",name="delete_cart")
     */
    public function deleteCArt($id)
    {
        $this->cartservices->deleteCart($id);

        return $this->redirectToRoute("app_cart");
    }
    /**
     * @Route("/deletefromcart/{id<[0-9]+>}",name="deletefrom_cart")
     */
    public function deletefromCArt($id)
    {
        $this->cartservices->deleteFromCart($id);

        return $this->redirectToRoute("app_cart");
    }
    /**
     * @Route("/addFavorite/{id<[0-9]+>}",name="add_favorite")
     */
    public function addfavorite($id)
    {
        $this->cartservices->addfavorite($id);

        return $this->redirectToRoute("app_produit_show", [
            "id" => $id
        ]);
    }
}
