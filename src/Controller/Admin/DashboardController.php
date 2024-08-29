<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\ContratDeMaintenance as Contrat;
use App\Entity\Facture;
use App\Entity\Relance;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator) {}

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(ClientCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CO2M Tools');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Clients');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Ajouter un client', 'fas fa-plus', Client::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste des clients', 'fas fa-eye', Client::class),
        ]);

        yield MenuItem::section('Contrats de maintenance');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Ajouter un contrat', 'fas fa-plus', Contrat::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste des contrats', 'fas fa-eye', Contrat::class),
        ]);

        yield MenuItem::section('Factures');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Ajouter une facture', 'fas fa-plus', Facture::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste des factures', 'fas fa-eye', Facture::class),
        ]);

        yield MenuItem::section('Relances');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Ajouter une relance', 'fas fa-plus', Relance::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Liste des relances', 'fas fa-eye', Relance::class),
        ]);
    }
}
