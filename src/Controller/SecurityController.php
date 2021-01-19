<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\UserRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('@EasyAdmin/page/login.html.twig', [
            // parameters usually defined in Symfony login forms
            'error' => $error,
            'last_username' => $lastUsername,

            // OPTIONAL parameters to customize the login form:

            // the translation_domain to use (define this option only if you are
            // rendering the login template in a regular Symfony controller; when
            // rendering it from an EasyAdmin Dashboard this is automatically set to
            // the same domain as the rest of the Dashboard)
            'translation_domain' => 'admin',

            // the title visible above the login form (define this option only if you are
            // rendering the login template in a regular Symfony controller; when rendering
            // it from an EasyAdmin Dashboard this is automatically set as the Dashboard title)
            'page_title' => 'Connexion',

            // the string used to generate the CSRF token. If you don't define
            // this parameter, the login form won't include a CSRF token
            'csrf_token_intention' => 'authenticate',

            // the URL users are redirected to after the login (default: '/admin')
            'target_path' => $this->generateUrl('admin'),

            // the label displayed for the username form field (the |trans filter is applied to it)
            'username_label' => 'Nom d\'utilisateur',

            // the label displayed for the password form field (the |trans filter is applied to it)
            'password_label' => 'Mot de passe',

            // the label displayed for the Sign In form button (the |trans filter is applied to it)
            'sign_in_label' => 'Se connecter',

            // the 'name' HTML attribute of the <input> used for the username field (default: '_username')
            'username_parameter' => 'username',

            // the 'name' HTML attribute of the <input> used for the password field (default: '_password')
            'password_parameter' => 'password',
        ]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator)
    {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user->setRoles(array('ROLE_USER'));
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form['plainPassword']->getData()
            ));
            if (true === $form['agreeTerms']->getData()) {
                $user->setAgreedTermsAt();
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Bienvenue sur le site {$user->getUsername()} !");

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profile/{username}/password", name="app_edit_password")
     */
    public function editPassword(User $user, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {   
        if ($user->getUsername() !== $this->getUser()->getUsername()) {     // prevents other users to acces the page
            throw $this->createAccessDeniedException();
        }
        
        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            if ($passwordEncoder->isPasswordValid($user, $form['oldPassword']->getData())) {
                $user->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $form['plainPassword']->getData()
                ));
                
                $entityManager->flush();
            
                $this->addFlash('success', "Votre mot de passe a bien été modifié.");
                return $this->redirectToRoute('app_user', [
                    'username' => $user->getUsername()
                ]);
            }
            else {
                $this->addFlash('danger', 'Le mot de passe actuel est invalide. Réessayez');
                return $this->render('security/changepassword.html.twig', [
                    'changePasswordForm' => $form->createView(),
                ]);
            }
        }
    
        return $this->render('security/changepassword.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }
}
