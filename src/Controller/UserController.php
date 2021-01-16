<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{   
    /**
    * @Route("/profile/{username}", name="app_user")
    */
    public function profile(User $user) 
    {   
        if ($user->getUsername() !== $this->getUser()->getUsername()) {     // prevents other users to acces the page
            throw $this->createAccessDeniedException();
        }
        
        return $this->render('user/profile_view.html.twig'); 
    }

    /**
     * @Route("/profile/{username}/edit", name="app_user_edit")
    */
    public function editProfile(User $user, Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager)
    {   
        if ($user->getUsername() !== $this->getUser()->getUsername()) {     // prevents other users to acces the page
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $avatarFile */
             $avatarFile = $form->get('imageFilename')->getData();
            
            if ($avatarFile) {
                $avatarFileName = $fileUploader->upload($avatarFile);
                $user->setImageFilename($avatarFileName);  
            }
            $entityManager->flush();

            $this->addFlash('info', 'Votre profil a été mis à jour.');
            return $this->redirectToRoute('app_user');
        }
    
        return $this->render('user/profile_edit.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
}
