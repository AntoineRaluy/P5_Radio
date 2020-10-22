<?php

namespace App\Controller;

use App\Entity\Track;
use App\Form\TrackFormType;
use App\Repository\TrackRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrackController extends AbstractController
{
     /**
     * @Route("/tracks", name="app_tracklist")
     */
    public function tracklist(TrackRepository $repository)
    {
        $tracks = $repository->findAllPostedTracksOrderedByArtist();

        return $this->render('track/tracklist.html.twig', [
            'tracks' => $tracks,
        ]);
    }

    /**
     * @Route("/tracks/new", name="app_track_new")
     */
    public function newtrack(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(TrackFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $track = $form->getData();
            $entityManager->persist($track);
            $entityManager->flush();

            return $this->redirectToRoute('app_tracklist');
        }

        return $this->render('track/newtrack.html.twig', [
            'trackForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tracks/{id}", name="app_track_show")
     */
    public function trackview(Track $track)
    {
        return $this->render('track/trackview.html.twig', [
            'track' => $track,
        ]);
    }

}
