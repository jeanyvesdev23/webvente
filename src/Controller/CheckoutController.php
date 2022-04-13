<?php

namespace App\Controller;

use App\Entity\Addres;
use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Form\AddresType;
use App\Repository\AddresRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use App\Services\Cartservices;
use Countable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function checkout(Request $request, EntityManagerInterface $em, ProduitRepository $produitRepository, AddresRepository $addresRepository, Cartservices $cartservices): Response
    {
        $users = $this->getUser();


        $carts = $cartservices->getFullCart();

        if (!isset($carts["produit"])) {
            return $this->redirectToRoute("app_produit");
        } elseif (!$users) {
            return $this->redirectToRoute("app_login");
        }



        $addre = new Addres;
        $form = $this->createForm(AddresType::class, $addre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $addre->setUser($users);
            $addresRepository->add($addre);
        }
        // dd($commande->getProduits());


        return $this->render('checkout/index.html.twig', [
            "users" => $users,
            "carts" => $carts,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/commande", name="app_commande")
     */
    public function commande(CommandeRepository $commandeRepository, Cartservices $cartservices): Response
    {
        // $users = $this->getUser();
        // $com = new Commande;
        // $com->setCodeCommande(new \DateTime());
        // $com->setQuantite(2);
        // $com->setSubTotal(2314);
        // $com->setUsers($users);
        // $com->addProduit((new Produit)->setNomPro("velo")->setPrixPro(123141)->setStatus(0)->setImagePro("ezzgesgs")->setCategorie((new Categorie)->setImageCate("sdfdsfs")->setNomCate("transport"))->setMarque((new Marque)->setNomMarque("bmx")->setEntreprise("bmx entreprise")->setImageMarque("qffqq")));
        // $commandeRepository->add($com);
        // dd($com);

        $commande = $commandeRepository->findAll();

        if (!$users) {
            return $this->redirectToRoute("app_login");
        }
        // foreach ($commandes as $id => $qte) {
        //     $produit[] = $produitRepository->find($id);
        // }
        // for ($i = 0; $i < count($commandes); $i++) {
        //     $users->addCommande($produit[$i]);
        // }


        dd($commande);









        return $this->render('checkout/commande.html.twig', [
            "commandes" => $commande,


        ]);
    }
}
