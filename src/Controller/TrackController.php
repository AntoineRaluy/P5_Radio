<?php

namespace App\Controller;

use App\Entity\Track;
use App\Form\TrackFormType;
use App\Service\MarkdownHelper;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function newtrack(Request $request, EntityManagerInterface $entityManager, TrackRepository $repoTrack)
    {
        $form = $this->createForm(TrackFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $track = $form->getData();
            $entityManager->persist($track);
            $entityManager->flush();

            return $this->redirectToRoute('app_tracklist');
        }
    
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);
        
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $trackSearch = $searchForm->getData();
            $resultTrack = $repoTrack->findTrack($trackSearch);

            if ($resultTrack == null) {
                $this->addFlash('erreur', 'Aucun morceau ne correspond dans la radio.');
            }
        }

        return $this->render('track/newtrack.html.twig', [
            'trackForm' => $form->createView(),
            'searchForm' => $searchForm->createView(),
        ]);
    }

    // public function searchTrack(Request $request, TrackRepository $track) {

    //     $searchForm = $this->createForm(SearchType::class);
    //     $searchForm->handleRequest($request);
        
    //     $data = $track->findAll();
 
    //     if ($searchForm->isSubmitted() && $searchForm->isValid()) {
    //         $title = $searchForm->getData()->getTitle();
    //         $data = $track->search($title);

    //         if ($data == null) {
    //             $this->addFlash('erreur', 'Aucun morceau ne correspond dans la radio.');
    //         }
    //     }
        
    //     return $this->render('track/newtrack.html.twig', [
    //         'searchForm' => $searchForm->createView(),
    //         ]);
    // }
    

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
