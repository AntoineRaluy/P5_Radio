<?php

namespace App\Controller;

use App\Entity\Track;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request): Response
    {
        $hostname = $request->getSchemeAndHttpHost();
        $pageUrls = [];
        
        $pageUrls[] = ['loc' => $this->generateUrl('app_homepage')];
        $pageUrls[] = ['loc' => $this->generateUrl('admin')];
        $pageUrls[] = ['loc' => $this->generateUrl('app_login')];
        $pageUrls[] = ['loc' => $this->generateUrl('app_register')];
        $pageUrls[] = ['loc' => $this->generateUrl('app_tracklist')];
        $pageUrls[] = ['loc' => $this->generateUrl('app_track_new')];
        $pageUrls[] = ['loc' => $this->generateUrl('app_request_track')];
        $pageUrls[] = ['loc' => $this->generateUrl('app_policies')];
        $pageUrls[] = ['loc' => $this->generateUrl('app_cookies')];
        $pageUrls[] = ['loc' => $this->generateUrl('app_contact')];
               
        foreach ($this->getDoctrine()->getRepository(Article::class)->findAll() as $article) {      
            $pageUrls[] = [
                'loc' => $this->generateUrl('app_article_show', [
                    'slug' => $article->getSlug(),
                ]),
                'lastmod' => $article->getUpdatedAt()->format('Y-m-d'),
            ];
        }

        foreach ($this->getDoctrine()->getRepository(Track::class)->findAll() as $track) {      
            $pageUrls[] = [
                'loc' => $this->generateUrl('app_track_show', [
                    'id' => $track->getId(),
                ]),
                'lastmod' => $track->getUpdatedAt()->format('Y-m-d'),
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', ['pageUrls' => $pageUrls,
                'hostname' => $hostname]),
            200
        );

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
