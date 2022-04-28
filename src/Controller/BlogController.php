<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\CommentaireBlog;
use App\Form\CommentaireBlogType;
use App\Repository\BlogRepository;
use App\Repository\CommentaireBlogRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(BlogRepository $blog): Response
    {
        return $this->render('blog/index.html.twig', [
            "blog" => $blog->findBy([], [], 4)
        ]);
    }
    /**
     * @Route("/blog/{id}/lire", name="app_blog_lire")
     */
    public function blogLire(Request $request, CommentaireBlogRepository $commentaire, Blog $blog): Response
    {
        $commenter = new CommentaireBlog;
        $form = $this->createForm(CommentaireBlogType::class, $commenter)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commenter->setBlog($blog)->setUsers($this->getUser())->setIsPublier(true);

            $commentaire->add($commenter);
            return $this->redirectToRoute("app_blog_lire", [
                "id" => $blog->getId()
            ]);
        }

        return $this->render('blog/lireblog.html.twig', [
            "blog" => $blog,
            "form" => $form->createView(),
            "commentaire" => $commentaire->findBy(["blog" => $blog->getId()], ["createdAt" => "DESC"], 2),
            "counts" => $commentaire->count(["blog" => $blog->getId()])
        ]);
    }
}
