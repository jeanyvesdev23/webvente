<?php

namespace App\Controller\Admin;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/marque")
 */
class MarqueController extends AbstractController
{
    /**
     * @Route("/", name="app_marque_index", methods={"GET","POST"})
     */
    public function index(MarqueRepository $marqueRepository, Request $request, SluggerInterface $sluggerInterface): Response
    {

        $limit = 6;
        $page = $request->query->get("page", 1);
        $offest = ($page - 1) * $limit;
        $marques = $marqueRepository->findBy([], [], $limit, $offest);
        $counts = $marqueRepository->count([]);
        $search = $request->request->get("search_marque");

        if ($search == "") {
            $marques;
        } else {

            $result = $marqueRepository->searchMarque($search, $offest, $limit);

            if ($result == null) {
                $marques;
            } else {
                $marques = $result;
                $counts = $marqueRepository->countMarque($search, $offest, $limit);
                foreach ($counts as $value) {
                    $count = $value;
                    foreach ($count as $value) {
                        $counts = (int)$value;
                    }
                }
            }
        }
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("imageMarque")->getData();
            if ($image) {
                $origineFile = pathinfo($image->getClientOriginalName(), 1);
                $slugerFile = $sluggerInterface->slug($origineFile);
                $newFile = $slugerFile . '' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move($this->getParameter("images_directory"), $newFile);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            $marque->setImageMarque($newFile);
            $marqueRepository->add($marque);
            return $this->redirectToRoute('app_marque_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('marque/index.html.twig', [
            'marques' => $marques,
            'counts' => $counts, "page" => $page, "limit" => $limit,
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/{id}", name="app_marque_show", methods={"GET"})
     */
    public function show(Marque $marque): Response
    {
        return $this->render('marque/show.html.twig', [
            'marque' => $marque,
        ]);
    }



    /**
     * @Route("/{id}", name="app_marque_delete", methods={"POST"})
     */
    public function delete(Request $request, Marque $marque, MarqueRepository $marqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $marque->getId(), $request->request->get('_token'))) {
            $marqueRepository->remove($marque);
        }

        return $this->redirectToRoute('app_marque_index', [], Response::HTTP_SEE_OTHER);
    }
}
