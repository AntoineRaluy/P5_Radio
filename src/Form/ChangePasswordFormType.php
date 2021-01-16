<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('oldPassword', PasswordType::class, [
            'label' => 'Mot de passe actuel :',
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Entrez votre mot de passe actuel.'
                ])
            ],
        ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'label' => false,
            'invalid_message' => 'Les deux mots de passe doivent être identiques.',
            'first_options'  => ['label' => 'Nouveau mot de passe :'],
            'second_options' => ['label' => 'Retapez le nouveau mot de passe :'],
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Entrez un mot de passe.'
                ]),
                new Length([
                    'min' => 8,
                    'minMessage' => 'Le mot de passe doit faire 8 caractères au minimum.'
                ])
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Enregistrer le nouveau mot de passe',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
