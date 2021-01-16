<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Track;
use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/eadmin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(TrackCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Beats\'N\'Roll Radio');
    }

    public function configureMenuItems(): iterable
    {   
        yield MenuItem::linkToUrl('Retour au site', 'fa fa-home', '/');
        yield MenuItem::section('Contenu du site');
        yield MenuItem::linkToCrud('Articles', 'fa fa-pen', Article::class);
        yield MenuItem::linkToCrud('Morceaux', 'fa fa-music', Track::class);
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Membres', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Commentaires', 'fa fa-comment', Comment::class);
        yield MenuItem::section(null);
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-door-open');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getUserName())
            // use this method if you don't want to display the name of the user
            ->displayUserName(true)
            // you can use any type of menu item, except submenus
            ->addMenuItems([
            ]);
    }
}
