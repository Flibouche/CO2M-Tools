<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        $commonFields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            EmailField::new('mail'),
            TextField::new('siret'),
            TextField::new('societe'),
            TelephoneField::new('telephone'),
        ];

        if ($pageName === Crud::PAGE_DETAIL) {
            $context = $this->adminContextProvider->getContext();
            $client = $context ? $context->getEntity()->getInstance() : null;

            if ($client && $client->getFactures()->count() > 0) {
                $commonFields[] = CollectionField::new('factures')
                    ->setEntryIsComplex(true)
                    ->onlyOnDetail()
                    ->setLabel('Factures')
                    ->setTemplatePath('admin/factures_list.html.twig');
            }

            // if ($client && $client->getContrats)
        }

        return $commonFields;
    }
}
