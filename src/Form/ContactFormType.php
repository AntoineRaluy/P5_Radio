<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Votre nom :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom.'
                    ])                        
                ],
            ])
            ->add('from', EmailType::class, [
                'label' => 'Email :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre adresse mail.'
                    ])
                ],
            ])
            ->add('object', null, [
                'label' => 'Objet du message :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un objet pour le message.'
                    ])
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer du texte dans le champ de message.'
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
