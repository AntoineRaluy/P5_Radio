<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{   
    /**
    * @IsGranted("ROLE_USER")
    * @Route("/articles/{slug}/flag{id}", name="app_flag_comment")
    */
    public function flagComment($slug, Comment $comment, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {      
        $comment->setIsFlagged(true);
        $entityManager->flush();

        $article = $articleRepository->findOneBy(['slug' => $slug]);        // identify the article with its slug

        $this->addFlash('info', 'Le commentaire est désormais en attente de modération.');

        return $this->redirectToRoute('app_article_show', [
            'slug' => $article->getSlug(),
        ]);
    }
}
