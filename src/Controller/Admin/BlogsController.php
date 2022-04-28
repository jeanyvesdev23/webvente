<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Repository\CommentaireBlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("admin/blogs")
 */
class BlogsController extends AbstractController
{
    /**
     * @Route("/", name="app_blogs_index", methods={"GET","POST"})
     */
    public function index(Request $request, BlogRepository $blogRepository, SluggerInterface $sluggerInterface): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("imageBog")->getData();
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
            $blog->setImageBog($newFile);
            $blog->setUser($this->getUser());
            $blogRepository->add($blog);
            return $this->redirectToRoute('app_blogs_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('blogs/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/commentaire", name="app_commentaire_blog", methods={"GET", "POST"})
     */
    public function commentaire(CommentaireBlogRepository $commentaireRepository): Response
    {

        return $this->render('blogs/commentaire.html.twig', [
            "commentaires" => $commentaireRepository->findAll()
        ]);
    }


    /**
     * @Route("/{id}", name="app_blogs_show", methods={"GET","POST"})
     */
    public function show(Blog $blog, Request $request, BlogRepository $blogRepository, SluggerInterface $sluggerInterface): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get("imageBog")->getData();
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
            $blog->setImageBog($newFile);
            $blogRepository->add($blog);
            return $this->redirectToRoute("app_blogs_index");
        }
        return $this->render('blogs/show.html.twig', [
            'blog' => $blog,
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/{id}", name="app_blogs_delete", methods={"POST"})
     */
    public function delete(Request $request, Blog $blog, BlogRepository $blogRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blog->getId(), $request->request->get('_token'))) {
            $blogRepository->remove($blog);
        }

        return $this->redirectToRoute('app_blogs_index', [], Response::HTTP_SEE_OTHER);
    }
}
