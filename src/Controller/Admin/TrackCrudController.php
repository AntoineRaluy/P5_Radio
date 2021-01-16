<?php

namespace App\Controller\Admin;

use App\Entity\Track;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TrackCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Track::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('Morceaux')->setEntityLabelInSingular('Morceau');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id')->hideOnForm(),
            TextField::new('artist', 'Artiste'),
            TextField::new('title', 'Titre'),
            TextField::new('genre', 'Genre'),
            NumberField::new('year', 'Année'),
            BooleanField::new('status', 'En ligne'),
        ];
    }
}
