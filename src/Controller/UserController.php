<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\MoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/profil")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/{id}", name="profile", methods={"GET"})
     * @param User $user
     * @param MoreRepository $moreRepository
     * @return Response
     */
    public function profile(User $user, MoreRepository $moreRepository): Response
    {
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'mores'=> $moreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @param MoreRepository $moreRepository
     * @return Response
     */
    public function new(Request $request, MoreRepository $moreRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'mores'=> $moreRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @param MoreRepository $moreRepository
     * @return Response
     */
    public function edit(Request $request, User $user, MoreRepository $moreRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'mores'=> $moreRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $session = new Session();
            $session->invalidate();

            $this->addFlash('sup', 'Votre compte a bien été supprimé');
        }

        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/{id}/mes_publications", name = "my_articles", methods = {"GET"})
     * @param User $user
     * @param ArticleRepository $articleRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function showMyArticles(User $user, ArticleRepository $articleRepository,CategoryRepository $categoryRepository): Response
    {
        return $this->render('user/my_articles.html.twig', [
            'user' => $user,
            'articles' => $articleRepository->findBy(['author'=>$user]),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}

