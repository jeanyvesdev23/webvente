<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, ContactRepository $contact): Response
    {
        $contacte = new Contact;
        $form = $this->createForm(ContactType::class, $contacte)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact->add($contacte);
            return $this->redirectToRoute("app_contact");
        }
        return $this->render('contact/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
