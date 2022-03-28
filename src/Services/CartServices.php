<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices
{
    private $session;
    private $tva = 2;
    private $productRepository;
    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }
    public function getCart()
    {
        return $this->session->get("cart", []);
    }
    public function addCart($id)
    {

        $cart = $this->getCart();
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }



        $this->updateCart($cart);
    }
    public function getFullCart()
    {
        $cart = $this->getCart();
        $fullCart = [];
        $quantityCart = 0;
        $total = 0;
        foreach ($cart as $id => $qte) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $fullCart["products"][] = [
                    "quantity" => $qte,
                    "product" => $product
                ];
                $quantityCart += $qte;
                $total += $qte * $product->getPriceProduct();
            } else {
                $this->deleteFromCart($id);
            }
        }
        $fullCart["data"] = [
            "quantityCart" => $quantityCart,
            "total" => $total,
            "taxe" => round($total * $this->tva, 2),
            "totalTTC" => round($total + ($total * $this->tva), 2)
        ];
        return $fullCart;
    }
    public function updateCart($cart)
    {
        $this->session->set("cart", $cart);
        $this->session->set("fullCart", $cart);
    }
    public function deleteFromCart($id)
    {
        $cart = $this->getCart();
        if (!empty($cart[$id]) > 2) {
            $cart[$id]--;
        }
        $this->updateCart($cart);
    }
    public function deleteAllToCart($id)
    {
        $cart = $this->getCart();
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $this->updateCart($cart);
        }
    }
    public function deleteCart()
    {
        $this->updateCart([]);
    }
}
