<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleLike;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Repository\AboutsRepository;
use App\Repository\ArticleLikeRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\HomepageRepository;
use App\Repository\MoreRepository;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ArticleRepository $articleRepository
     * @param HomepageRepository $homepageRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, HomepageRepository $homepageRepository): Response
    {

        return $this->render('default/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'homepages' => $homepageRepository->findAll(),

        ]);
    }

    /**
     * @Route("/a-propos", name="about")
     * @param AboutsRepository $aboutsRepository
     * @return Response
     */
    public function about(AboutsRepository $aboutsRepository): Response
    {
        return $this->render('default/abouts.html.twig', [
            'abouts' => $aboutsRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="comment_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function deleteComment(Request $request, Comment $comment): Response
    {
        $article = $comment->getArticle();

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('article_details', ['id' => $article->getId()]);
    }

    /**
     * @Route("/{id}/modifier", name="comment_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function editComment(Request $request, Comment $comment): Response
    {
        $article = $comment->getArticle();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('article_details', ['id' => $article->getId()]);
        }

        return $this->render('default/edit_comment.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/publications", name="all_articles")
     * @param ArticleRepository $articleRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */

    public function allArticles(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response

    {
        return $this->render('default/all_articles.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param Mailer $mailer
     * @return Response
     */
    public function contact(Request $request, Mailer $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $contact->setCreatedAt(new DateTime());
            $entityManager->persist($contact);
            $entityManager->flush();

            $mailer->notifEmail($contact);

            return $this->redirectToRoute('merci');
        }
        return $this->render('default/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/politics", name="politics")
     * @param MoreRepository $moreRepository

     * @return Response
     */
    public function politics(MoreRepository $moreRepository): Response
    {
        return $this->render('default/politics.html.twig', [
            'mores' => $moreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/merci", name="merci")
     * @param $moreRepository
     * @return Response
     */
    public function thanks(MoreRepository $moreRepository): Response
    {
        return $this->render('default/thanks.html.twig', [
            'mores' => $moreRepository->findAll(),
        ]);
    }


    /**
     * @Route("/all-article-like/{id}", name="all_article_like")
     * @param Article $article
     * @param ArticleLikeRepository $articleLikeRepository
     * @return JsonResponse
     */
    public function getInAllArticle (Article $article, ArticleLikeRepository $articleLikeRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($article->isLikedByUser($user)) {
            $like = $articleLikeRepository->findOneBy(['article' => $article, 'user' => $user]);
            $em->remove($like);
            $em->flush();
            return $this->json([
                'likes' => $articleLikeRepository->count(['article' => $article])
            ], 200
            );
        }

        $like = new ArticleLike();
        $like->setArticle($article)->setUser($user);
        $em->persist($like);
        $em->flush();

        return $this->json(['likes' => $articleLikeRepository->count(['article' => $article])], 200);
    }

}
