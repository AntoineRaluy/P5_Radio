<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
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
    public function flagComment(Comment $comment, Article $article, EntityManagerInterface $entityManager)
    {      
        dd($article);
        $comment->setIsFlagged(true);

        $entityManager->flush();

        return $this->redirectToRoute('app_article_show', [
            'slug' => $article->getSlug(),
        ]);
    }
}
