<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\Relance;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

#[IsGranted('ROLE_ADMIN')]
class ClientCrudController extends AbstractCrudController
{
    private AdminContextProvider $adminContextProvider;

    public function __construct(AdminContextProvider $adminContextProvider)
    {
        $this->adminContextProvider = $adminContextProvider;
    }

    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', '%entity_label_plural% listing')
            ->setPageTitle('detail', fn(Client $client) => (string) $client)
            ->setPageTitle('edit', fn(Client $client) => 'Edit ' . (string) $client);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        $context = $this->adminContextProvider->getContext();
        $client = $context ? $context->getEntity()->getInstance() : null;

        $commonFields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom', 'Prénom'),
            EmailField::new('mail'),
            TextField::new('siret', 'N° de SIRET'),
            TextField::new('societe', 'Société'),
            TelephoneField::new('telephone', 'Téléphone'),
            CollectionField::new('factures')->onlyOnIndex(),
            CollectionField::new('contrats')->onlyOnIndex(),
        ];

        if ($pageName === Crud::PAGE_DETAIL) {
            if ($client && $client->getFactures()->count() > 0) {
                $commonFields[] = CollectionField::new('factures')
                    ->setEntryIsComplex(true)
                    ->onlyOnDetail()
                    ->setLabel('Factures')
                    ->setTemplatePath('admin/factures_list.html.twig');
            }

            if ($client && $client->getContrats()->count() > 0) {
                $commonFields[] = CollectionField::new('contrats')
                    ->setEntryIsComplex(true)
                    ->onlyOnDetail()
                    ->setLabel('Contrats')
                    ->setTemplatePath('admin/contrats_list.html.twig');
            }

            // if ($client->getFactures()->exists(fn($key, $facture) => $facture->getRelances()->count() > 0)) {
            //     $commonFields[] = CollectionField::new('factures')
            //         ->setLabel('Relances')
            //         ->setEntryType(Relance::class)
            //         ->onlyOnDetail()
            //         ->setTemplatePath('admin/relances_list.html.twig');
            // }
        }

        return $commonFields;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return parent::configureAssets($assets)
            ->addCssFile('styles/app.css');
    }
}
