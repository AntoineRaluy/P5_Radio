<?php

namespace App\Controller;

use App\Entity\Track;
use App\Form\TrackFormType;
use App\Form\NewTrackFormType;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
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
            $title = $track->getTitle();
            $artist = $track->getArtist();
                                                    
            if (($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_USER')) == true) {
                $user= $this->get('security.token_storage')->getToken()->getUser();
                $track->setContributor($user);      // set contributor if member of the site
            }            

            $resultTrack = $repoTrack->findTrack($artist, $title);
            
            if ($resultTrack == !null) {
                $this->addFlash('erreur', 'Le morceau demandé est déjà dans la radio.');
                return $this->redirectToRoute('app_tracklist');
                }
            else {
                $entityManager->persist($track);
                $entityManager->flush();

                $this->addFlash('info', 'Le morceau est maintenant en attente de validation.');
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

    /**
     * @Route("/contact/req", name="app_request_track")
     */
    public function requestTrack(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(NewTrackFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reqTrack = $form->getData();

            $email = (new TemplatedEmail())
                ->from($reqTrack['from'])
                ->to('antoine.raluy@hotmail.fr')
                ->subject('[RADIO Request New Track]')
                ->htmlTemplate('email/reqtrackmail.html.twig')
                ->context([
                    'artist' => $reqTrack['artist'],
                    'title' => $reqTrack['title'],
                    'from' => $reqTrack['from'],
                    'source' => $reqTrack['source']
                ]);
            $mailer->send($email);   
            
            $this->addFlash('info', 'Votre demande a bien été prise en compte.');

            return $this->redirectToRoute('app_track_new');
        }

        return $this->render('track/requesttrack.html.twig', [
            'newTrackForm' => $form->createView(),
        ]);
    }
}
