<?php

namespace App\Controller;

use App\Service\MarkdownHelper;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $logger;
    private $isDebug;

    // public function __construct(LoggerInterface $logger, bool $isDebug)
    // {
    //     $this->logger = $logger;
    //     $this->isDebug = $isDebug;
    // }


    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('article/homepage.html.twig');
    }

    /**
     * @Route("/articles/{slug}", name="app_article_show")
     */
    public function show($slug, MarkdownHelper $markdownHelper)
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode!');
        }

        $acontent = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];
        $articleText = 'I\'ve been turned into a cat, any *thoughts* on how to turn back? While I\'m **adorable**, I don\'t really care for cat food.';

        $parsedArticleText = $markdownHelper->parse($articleText);

        return $this->render('question/show.html.twig', [
            'article' => ucwords(str_replace('-', ' ', $slug)),
            'articleText' => $parsedArticleText,
            'acontent' => $acontent,
        ]);
    }

    /**
     * @Route("/articles/new")
     */
    public function new()
    {
        return new Response('Time for some Doctrine magic!');
    }
}
