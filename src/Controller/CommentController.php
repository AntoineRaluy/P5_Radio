<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{   
    /**
    * @Route("/articles/{slug}/flag{id}", name="app_flag_comment")
     */
    public function flagComment($slug, Comment $comment, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {      
        $comment->setIsFlagged(true);
        $entityManager->flush();

        $article = $articleRepository->findOneBy(['slug' => $slug]);
        return $this->redirectToRoute('app_article_show', [
            'slug' => $article->getSlug(),
        ]);
    }
}
