<?php

namespace App\Controller;

use App\Entity\Addres;
use App\Form\AddresType;
use App\Repository\AddresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/addres")
 */
class AddresController extends AbstractController
{
    /**
     * @Route("/", name="app_addres_index", methods={"GET"})
     */
    public function index(AddresRepository $addresRepository): Response
    {
        return $this->render('addres/index.html.twig', [
            'addres' => $addresRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_addres_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AddresRepository $addresRepository): Response
    {
        $addre = new Addres();
        $form = $this->createForm(AddresType::class, $addre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addre->setUser($this->getUser());
            $addresRepository->add($addre);
            return $this->redirectToRoute('app_compte', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('addres/new.html.twig', [
            'addre' => $addre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_addres_show", methods={"GET"})
     */
    public function show(Addres $addre): Response
    {
        return $this->render('addres/show.html.twig', [
            'addre' => $addre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_addres_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Addres $addre, AddresRepository $addresRepository): Response
    {
        $form = $this->createForm(AddresType::class, $addre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addresRepository->add($addre);
            return $this->redirectToRoute('app_addres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('addres/edit.html.twig', [
            'addre' => $addre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_addres_delete", methods={"POST"})
     */
    public function delete(Request $request, Addres $addre, AddresRepository $addresRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $addre->getId(), $request->request->get('_token'))) {
            $addresRepository->remove($addre);
        }

        return $this->redirectToRoute('app_addres_index', [], Response::HTTP_SEE_OTHER);
    }
}
