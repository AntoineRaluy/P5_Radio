<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('Utilisateurs')->setEntityLabelInSingular('Utilisateur');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id')->hideOnForm(),
            TextField::new('firstName', 'Prénom'),
            TextField::new('username', 'Pseudo'),
            TextField::new('password', 'Mot de passe')->hideOnIndex(),
            EmailField::new('email', 'Adresse mail')->hideOnIndex(),
            ChoiceField::new('roles', 'Role')->setChoices([
                'Membre' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices(),
            UrlField::new('imageFilename', 'URL de l\'avatar')->hideOnIndex(),
            DateField::new('createdAt', 'Inscrit le'),
        ];
    }

}