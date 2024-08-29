<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;

#[IsGranted('ROLE_ADMIN')]
class FactureCrudController extends AbstractCrudController
{
    // AdminContextProvider me permet de récupérer l'instance de l'entité actuellement affichée, je l'injecte donc dans le constructeur
    private AdminContextProvider $adminContextProvider;

    public function __construct(AdminContextProvider $adminContextProvider)
    {
        $this->adminContextProvider = $adminContextProvider;
    }

    public static function getEntityFqcn(): string
    {
        return Facture::class;
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
            AssociationField::new('client'),
            DateField::new('dateFacturation'),
            BooleanField::new('genere'),
            BooleanField::new('paye'),
            DateField::new('dateEnvoi'),
        ];

        // Si la page actuelle est la page de détail, je récupère l'instance de la facture actuellement affichée
        if ($pageName === Crud::PAGE_DETAIL) {
            $context = $this->adminContextProvider->getContext();
            $facture = $context ? $context->getEntity()->getInstance() : null;

            // Si la facture actuellement affichée a des relances, j'ajoute un champ CollectionField pour afficher les relances
            if ($facture && $facture->getRelances()->count() > 0) {
                $commonFields[] = CollectionField::new('relances')
                    ->setEntryIsComplex(true)
                    ->onlyOnDetail()
                    ->setLabel('Relances')
                    ->setTemplatePath('admin/relances_list.html.twig');
            }
        }

        return $commonFields;
    }
}
