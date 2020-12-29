<?php

namespace App\Controller\Admin;

use App\Entity\Track;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
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

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('artist'),
            TextField::new('title'),
            TextField::new('genre'),
            NumberField::new('year'),
            TextField::new('mbid'),
            BooleanField::new('status'),
        ];
    }
}
