<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("admin/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/", name="app_user", methods={"GET"})
     */
    public function index(UserRepository $user, Request $request): Response
    {
        $limit = 6;
        $page = $request->query->get("page", 1);
        $offest = ($page - 1) * $limit;
        $user = $user->findBy([], [], $limit, $offest);



        return $this->render('user/index.html.twig', [
            'user' => $user

        ]);
    }





    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(UserType::class, $user, ["emailAndRole" => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = $request->request->get("roleA");
            $role = $request->request->get("roleU");
            $user->setRoles([$role]);
            $em->flush();
            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [

            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }
}
