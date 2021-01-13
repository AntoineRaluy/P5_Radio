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

        if ($form->isSubmitted()) {
            $track = $form->getData();
            $title = $track->getTitle();
            $artist = $track->getArtist();

            if (($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_USER')) == true) {
                $user= $this->get('security.token_storage')->getToken()->getUser();
                $track->setContributor($user);
            }
            

            $resultTrack = $repoTrack->findTrack($artist, $title);
            
            if ($resultTrack == !null) {
                $this->addFlash('erreur', 'Le morceau est déjà dans la radio');
                return $this->redirectToRoute('app_tracklist');
                }

            else {

                $entityManager->persist($track);
                $entityManager->flush();
                $this->addFlash('info', 'Le morceau est maintenant en attente de validation');
                return $this->redirectToRoute('app_tracklist');
            }
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
