<?php

namespace App\Controller\Client;


use App\Form\CheckoutType;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use App\Services\Cartservices;
use App\Services\OrderServices;
use Countable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController implements Countable
{
    public function count(): int
    {
        return count($this);
    }
    /**
     * @Route("/checkout", name="app_checkout")
     */
    public function checkout(Cartservices $cartservices, SessionInterface $session, Request $request, OrderServices $orderServices, CommandeRepository $commande): Response
    {
        $users = $this->getUser();
        $carts = $cartservices->getFullCart();
        $session->get('checkout_data', []);

        if (!isset($carts["produit"])) {
            $this->addFlash('info', "Désolé vous panier est vide");

            return $this->redirectToRoute("app_produit");
        } elseif (!$users) {
            $this->addFlash('info', "Il faut être connecté");

            return $this->redirectToRoute("app_login");
        } elseif ($users->getAddres()->getValues() == null) {
            $this->addFlash('info', "Désolé il faut une addresse");

            return $this->redirectToRoute("app_addres_new");
        }
        $form = $this->createForm(CheckoutType::class,  null, ['user' => $users])->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session->get('checkout_data', []);
            if ($session->get("checkout_data", [])) {

                $data = $session->get("checkout_data", []);
            } else {
                $data = $form->getData();
                $session->set("checkout_data", $data);
                $data = $session->get("checkout_data", []);
            }

            $carts["checkout"] = $data;
            $orderServices->savecart($carts, $users);
            $commande = $commande->findAll();
            $this->addFlash("warning", "Commande effectué");

            return $this->redirectToRoute('app_commande', [
                'id' => count($commande) + 83
            ]);
        }

        return $this->render('checkout/index.html.twig', [
            "carts" => $carts,
            "form" => $form->createView()

        ]);
    }

    /**
     * @Route("/commande/{id}", name="app_commande")
     */
    public function commande($id, Cartservices $cart, PanierRepository $panier, CommandeRepository $commande): Response
    {

        $cart->deleteAllCart();
        $commande = $commande->find($id);

        $panier = $panier->findBy(["commande" => $id]);



        return $this->render('checkout/commande.html.twig', [
            "panier" => $panier,
            "commande" => $commande
        ]);
    }
}
