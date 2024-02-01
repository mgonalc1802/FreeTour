<?php

namespace App\Controller\Admin;

use App\Entity\{User, Item, Provincia, Localidad};
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/rutas', name: 'rutas')]
    public function rutas(): Response
    {
        // return parent::index();
        return $this->render('ruta/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('FreeTour');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Front', 'fa fa-home', "/");
        yield MenuItem::section('Entidades');
        yield MenuItem::linkToCrud('Usuarios', 'fa fa-users', User::class);
        yield MenuItem::linkToRoute('Rutas', 'fa fa-map-o', "rutas");
        yield MenuItem::linkToCrud('Items', 'fa fa-inbox', Item::class);
        yield MenuItem::section('Lugares');
        yield MenuItem::linkToCrud('Provincia', 'fa fa-map-marker', Provincia::class);
        yield MenuItem::linkToCrud('Localidad', 'fa fa-map-pin', Localidad::class);



    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addCssFile("css/estilo/admin.css");
    }
}
