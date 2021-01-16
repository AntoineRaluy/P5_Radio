<?php

namespace App\Form;

use App\Entity\Track;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class TrackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Nom de l\'artiste :',
                'attr' => [
                    'class' => 'data-entry-title',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Titre du morceau obligatoire.'
                    ])
                ],
            ])
            ->add('artist', null, [
                'label' => 'Titre du morceau :',
                'attr' => [
                    'class' => 'data-entry-artist',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Nom de l\'artiste obligatoire.'
                    ])
                ],
            ])
            ->add('genre', null, [
                'label' => 'Genre musical du morceau :',
                'attr' => [
                    'class' => 'data-entry-genre',
                ]
            ])
            ->add('year', null, [
                'label' => 'Année de parution :',
                'attr' => [
                    'class' => 'data-entry-year',
                ]
            ])
            ->add('mbid', HiddenType::class, [
                'attr' => [
                    'class' => 'data-entry-mbid',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Track::class,
        ]);
    }
}
