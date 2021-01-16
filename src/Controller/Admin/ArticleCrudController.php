<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('Articles')->setEntityLabelInSingular('Article');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextEditorField::new('content', 'Contenu'),
            AssociationField::new('author', 'Auteur'),
            DateField::new('createdAt', 'Créé le'),
            DateField::new('updatedAt', 'Modifié le'),
        ];
    }
}
