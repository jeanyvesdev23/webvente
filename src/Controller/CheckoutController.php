<?php

namespace App\Controller;

use App\Services\Cartservices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="app_checkout")
     */
    public function index(Cartservices $cartservices): Response
    {
        dd($users = $this->getUser()->getAddres()->getValues());
        $carts = $cartservices->getFullCart();
        if (!isset($carts["produit"])) {
            return $this->redirectToRoute("app_produit");
        } elseif (!$users) {
            return $this->redirectToRoute("app_login");
        } elseif (!$users->g) {
            # code...
        }
        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
        ]);
    }
}
