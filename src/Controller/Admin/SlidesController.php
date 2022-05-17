<?php

namespace App\Controller\Admin;

use App\Entity\Slides;
use App\Form\SlidesType;
use App\Repository\SlidesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("admin/slides")
 */
class SlidesController extends AbstractController
{
    /**
     * @Route("/", name="app_slides_index", methods={"GET","POST"})
     */
    public function index(Request $request, SlidesRepository $slidesRepository, SluggerInterface $sluggerInterface): Response
    {
        $limit = 6;
        $page = $request->query->get("page", 1);
        $offest = ($page - 1) * $limit;
        $slides = $slidesRepository->findBy([], [], $limit, $offest);
        $counts = $slidesRepository->count([]);
        $search = $request->query->get("search_slides");

        if ($search == "") {
            $slides;
        } else {

            $result = $slidesRepository->searchSlides($search, $offest, $limit);

            if ($result == null) {
                $slides;
            } else {
                $slides = $result;
                $counts = $slidesRepository->countSlides($search);
                foreach ($counts as $value) {
                    $count = $value;
                    foreach ($count as $value) {
                        $counts = (int)$value;
                    }
                }
            }
        }

        $slide = new Slides;
        $form = $this->createForm(SlidesType::class, $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("imageSlides")->getData();
            if ($image) {
                $origineFile = pathinfo($image->getClientOriginalName(), 1);
                $slugerFile = $sluggerInterface->slug($origineFile);
                $newFile = $slugerFile . '' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move($this->getParameter("images_directory"), $newFile);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                $slide->setImageSlides($newFile);
            }
            $slidesRepository->add($slide);
            return $this->redirectToRoute('app_slides_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('slides/index.html.twig', [
            'slides' => $slides,
            'counts' => $counts, "page" => $page, "limit" => $limit,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="app_slides_show", methods={"GET","POST"})
     */
    public function show(Slides $slide, Request $request, EntityManagerInterface $em, SluggerInterface $sluggerInterface): Response
    {
        $form = $this->createForm(SlidesType::class, $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("imageSlides")->getData();
            if ($image) {
                $origineFile = pathinfo($image->getClientOriginalName(), 1);
                $slugerFile = $sluggerInterface->slug($origineFile);
                $newFile = $slugerFile . '' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move($this->getParameter("images_directory"), $newFile);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                $slide->setImageSlides($newFile);
            }
            $em->flush();
            return $this->redirectToRoute('app_slides_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('slides/show.html.twig', [
            'slide' => $slide,
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/{id}", name="app_slides_delete", methods={"POST"})
     */
    public function delete(Request $request, Slides $slide, SlidesRepository $slidesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $slide->getId(), $request->request->get('_token'))) {
            $slidesRepository->remove($slide);
        }

        return $this->redirectToRoute('app_slides_index', [], Response::HTTP_SEE_OTHER);
    }
}
