<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/publication")
 */
class ArticleController extends AbstractController
{

    /**
     * @Route("/ajouter", name="article_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $article->setAuthor($user);
            $article->setIsPublished(false);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_details', [
                    'id' => $article->getId()
                ]
            );
        }
        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Article $article
     * @return Response
     */
    public function editArticle(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_details', ['id' => $article->getId()]);
        }
        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/details/{id}", name="article_details")
     * @param Article $article
     * @param Request $request
     * @param $commentRepository
     * @return Response
     */
    public function articleDetails(Article $article, Request $request,CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $comment->setArticle($article);
            $comment->setAuthor($user);
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        return $this->render('article/article_details.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'comments' => $commentRepository->findAll(),
        ]);
    }



    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     * @param Request $request
     * @param $article
     * @return Response
     */
    public function delete(Request $request, Article $article): Response
    {
       $user = $article ->getAuthor();
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

    }
        return $this->redirectToRoute('my_articles', ['id' => $user->getId()]);
}

}
