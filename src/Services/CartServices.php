<?php

namespace App\Services;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cartservices
{
    private $session;
    private $produitRepository;
    public function __construct(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $this->session = $session;
        $this->produitRepository = $produitRepository;
    }

    public function getcart()
    {
        return $this->session->get("cart", []);
    }
    public function updateCart($cart)
    {
        $this->session->set("cart", $cart);
        $this->session->set("cartData", $this->getFullCart());
    }
    public function addCart($id)
    {
        $cart = $this->getcart();
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->updateCart($cart);
    }
    public function getFullCart()
    {
        $cart = $this->getcart();
        $fullCart = [];
        $totalqte = 0;
        $subTotal = 0;
        foreach ($cart as $id => $qte) {
            $product = $this->produitRepository->find($id);
            if ($product) {
                $fullCart["produit"][] = [
                    "quantite" => $qte,
                    "produit" => $product
                ];
                $totalqte += $qte;
                $subTotal += $qte * $product->getPrixPro();
            } else {
                $this->deleteCart($cart);
            }
            $fullCart["data"] = [
                "total" => $subTotal,
                "totalqte" => $totalqte
            ];
            # code...
        }

        return $fullCart;
    }
    public function deleteCart($id)
    {
        $cart = $this->session->set("cart", []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
        }
        $this->updateCart($cart);
    }
    public function deleteAllCart()
    {
        $this->updateCart([]);
    }
}
