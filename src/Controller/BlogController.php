<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\CommentaireBlog;
use App\Entity\WishList;
use App\Form\CommentaireBlogType;
use App\Repository\BlogRepository;
use App\Repository\CommentaireBlogRepository;
use App\Repository\WishListRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            $this->addFlash("info", "Commentaire bien ajouter");
            return $this->redirectToRoute("app_blog_lire", [
                "id" => $blog->getId()
            ]);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash("danger", "Commentaire n'est pas ajouter");
        }

        return $this->render('blog/lireblog.html.twig', [
            "blog" => $blog,
            "form" => $form->createView(),
            "commentaire" => $commentaire->findBy(["blog" => $blog->getId(), "isPublier" => true], ["createdAt" => "DESC"], 2),

        ]);
    }
    /**
     * @Route("/likes/{id<[0-9]+>}",name="app_likes")
     */
    public function addfavorite(Blog $id, EntityManagerInterface $em, WishListRepository $wishLists): Response
    {
        $user = $this->getUser();
        if ($id->isWishlist($user)) {
            $wish = $wishLists->findOneBy(["blog" => $id, "user" => $user]);
            $em->remove($wish);
            $em->flush();
            return $this->json(['code' => 200, "message" => "wish remove", "data" => $wishLists->count(["blog" => $id])], 200);
        }
        $wishList = new WishList;
        $wishList->setBlog($id)->setUser($user);
        $em->persist($wishList);
        $em->flush();

        return $this->json([
            "code" => 200, "message" => "wish ajouter", "data" => $wishLists->count(["blog" => $id])
        ], 200);
    }
}
