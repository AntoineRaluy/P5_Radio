<?php

namespace App\Form;

use App\Entity\Track;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'attr' => [
                        'class' => 'data-entry-title',
                        ]
            ])
            ->add('artist', null, [
                'attr' => [
                        'class' => 'data-entry-artist',
                        ]
            ])
            ->add('genre', null, [
                'attr' => [
                        'class' => 'data-entry-genre',
                        ]
            ])
            ->add('year', null, [
                'attr' => [
                        'class' => 'data-entry-year',
                        ]
            ])
            ->add('mbid', null, [
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
