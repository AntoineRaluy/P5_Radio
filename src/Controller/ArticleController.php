<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Service\MarkdownHelper;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
     /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->findAllPostedOrderedByNewest();

        return $this->render('article/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/articles/{slug}", name="app_article_show")
     */
    public function show(Article $article, Request $request, EntityManagerInterface $entityManager)
    {   
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $user= $this->get('security.token_storage')->getToken()->getUser();
            $comment->setAuthor($user);
            $comment->setArticle($article);
            $comment->setCreatedAt(new \DateTime('now'));
            
            $entityManager->persist($comment);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_article_show', [
                'slug' => $article->getSlug()
            ]);
        }
        
        return $this->render('article/show.html.twig', [
            'commentForm' => $form->createView(),
            'article' => $article,
            'comment' => $comment,
        ]);
    }
}