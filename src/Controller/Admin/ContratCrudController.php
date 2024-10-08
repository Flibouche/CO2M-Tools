<?php

namespace App\Controller\Admin;

use App\Entity\Contrat;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

#[IsGranted('ROLE_ADMIN')]
class ContratCrudController extends AbstractCrudController
{
    private AdminContextProvider $adminContextProvider;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminContextProvider $adminContextProvider, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminContextProvider = $adminContextProvider;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Contrat::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        $context = $this->adminContextProvider->getContext();
        $contrat = $context ? $context->getEntity()->getInstance() : null;

        $commonFields = [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('client'),
            DateField::new('dateDebut', 'Date de début'),
            DateField::new('dateFin', 'Date de fin'),
            ChoiceField::new('tarification', 'Type de tarification'),
            MoneyField::new('tarif')->setCurrency('EUR')->setStoredAsCents(false),
        ];

        return $commonFields;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return parent::configureAssets($assets)
            ->addCssFile('styles/app.css');
    }
}
