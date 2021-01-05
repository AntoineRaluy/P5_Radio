<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserController extends AbstractController
{   
    /**
    * @Route("/user", name="app_user")
    */
    public function index() 
    {
        return $this->render('user/profile_view.html.twig'); 
    }

    /**
     * @Route("/user/{username}/edit", name="app_user_edit")
    */
    public function editProfile(User $user, Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager)
    {
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

                return $this->redirectToRoute('app_user');
            }
    
            return $this->render('user/profile_edit.html.twig', [
                'userForm' => $form->createView(),
            ]);
    }
}
