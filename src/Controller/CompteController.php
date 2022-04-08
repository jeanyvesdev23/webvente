<?php

namespace App\Controller;

use App\Repository\AddresRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    /**
     * @Route("/compte", name="app_compte")
     */
    public function index(UserRepository $userRepository, AddresRepository $addresRepository): Response
    {
        $users = $this->getUser();
        if (!$users) {
            return $this->redirectToRoute("app_login");
        }



        return $this->render('compte/index.html.twig', [
            "users" => $users
        ]);
    }
}
