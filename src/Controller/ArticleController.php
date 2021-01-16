<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
     /**
     * @Route("/", name="app_homepage")
     */
    public function index(ArticleRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $data = $repository->findAllPostedOrderedByNewest();    

        $articles = $paginator->paginate(           // 3 articles per page
            $data,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('article/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/articles/{slug}", name="app_article_show")
     */
    public function displayArticle(Article $article, Request $request, EntityManagerInterface $entityManager)
    {   
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $user= $this->get('security.token_storage')->getToken()->getUser();     // get current user
            $comment->setAuthor($user);
            $comment->setArticle($article);
            $comment->setCreatedAt(new \DateTime('now'));
            
            $entityManager->persist($comment);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_article_show', [
                'slug' => $article->getSlug()
            ]);
        }
        
        return $this->render('article/article.html.twig', [
            'commentForm' => $form->createView(),
            'article' => $article,
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/policies", name="app_policies")
     */
    public function policies()
    {
        return $this->render('policies.html.twig');
    }

    /**
     * @Route("/cookies", name="app_cookies")
     */
    public function cookies()
    {
        return $this->render('cookies.html.twig');
    }
}   