<?php

namespace App\Controller;


use App\Services\CartServices;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private $cart;
    public function __construct(CartServices $cart)
    {
        $this->cart = $cart;
    }
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(): Response
    {

        $products = $this->cart->getFullCart();
        if (!isset($products["products"])) {
            $this->redirectToRoute("app_produit");
        }


        return $this->render('cart/index.html.twig', compact('products'));
    }
    /**
     * @Route("/add/cart/{id<[0-9]+>}", name="app_add_cart")
     */
    public function addCart($id): Response
    {
        $this->cart->addCart($id);
        return $this->redirectToRoute('app_cart');
    }
    /**
     * @Route("/delete/cart/{id<[0-9]+>}", name="app_deleteAll_cart")
     */
    public function deleteAllToCart($id): Response
    {
        $this->cart->deleteAllToCart($id);
        return $this->redirectToRoute('app_cart');
    }
    /**
     * @Route("/delete/cart", name="app_delete_cart")
     */
    public function deleteCart(): Response
    {
        $this->cart->deleteCart();
        return $this->redirectToRoute('app_produit');
    }
    /**
     * @Route("/deleteFrom/cart/{id<[0-9]+>}", name="app_deleteFrom_cart")
     */

    public function deleteFromCart($id)
    {
        $this->cart->deleteFromCart($id);
        return $this->redirectToRoute('app_produit');
    }
}
