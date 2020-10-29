<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function contactAdmin(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $email = (new TemplatedEmail())
                ->from($contact['from'])
                ->to('antoine.raluy@hotmail.fr')
                ->subject('[RADIO Contact Form] ' . $contact['object'])
                ->text($contact['message'])
                ->htmlTemplate('email/contactmail.html.twig')
                ->context([
                    'name' => $contact['name'],
                    'object' => $contact['object'],
                    'message' => $contact['message']
                ]);
            $mailer->send($email);                

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
