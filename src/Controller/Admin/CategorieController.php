<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use symfony\Component\HttpFoundation\File\UploadedFile;
use symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("admin/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="app_categorie_index", methods={"GET","POST"})
     */
    public function index(CategorieRepository $categorieRepository, Request $request, SluggerInterface $sluggerInterface): Response
    {
        $limit = 6;
        $page = $request->query->get("page", 1);
        $offest = ($page - 1) * $limit;
        $categories = $categorieRepository->findBy([], [], $limit, $offest);
        $counts = $categorieRepository->count([]);
        $search = $request->request->get("searcg_categorie");

        if ($search == "") {
            $categories;
        } else {

            $result = $categorieRepository->searchCategorie($search, $offest, $limit);

            if ($result == null) {
                $categories;
            } else {
                $categories = $result;
                $counts = $categorieRepository->countCategorie($search, $offest, $limit);
                foreach ($counts as $value) {
                    $count = $value;
                    foreach ($count as $value) {
                        $counts = (int)$value;
                    }
                }
            }
        }
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("imageCate")->getData();
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
            $categorie->setImageCate($newFile);
            $categorieRepository->add($categorie);

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'counts' => $counts,
            "form" => $form->createView(), "page" => $page, "limit" => $limit, "offest" => $offest
        ]);
    }




    /**
     * @Route("/{id}", name="app_categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_categorie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository, SluggerInterface $sluggerInterface): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("imageCate")->getData();
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
            $categorie->setImageCate($newFile);
            $categorieRepository->add($categorie);
            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('_token'))) {
            $categorieRepository->remove($categorie);
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
