<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NewTrackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artist', null, [
                'label' => 'Nom de l\'artiste :'
            ])
            ->add('title', null, [
                'label' => 'Titre du morceau :'
            ])
            ->add('source', UrlType::class, [
                'label' => 'Adresse du site avec des détails sur le morceau :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir un site avec les informations sur le morceau.'
                    ])
                ],
            ])
            ->add('from', EmailType::class, [
                'label' => 'Votre adresse mail :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre adresse mail.'
                    ])
                ],
            ]);   
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
