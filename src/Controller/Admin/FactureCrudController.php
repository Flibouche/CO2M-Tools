<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FactureCrudController extends AbstractCrudController
{
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

        if ($pageName === Crud::PAGE_DETAIL) {
            return array_merge($commonFields, [
                CollectionField::new('relances')
                    ->setEntryIsComplex(true)
                    ->onlyOnDetail()
                    ->setLabel('Relances')
                    ->setTemplatePath('admin/relances_list.html.twig')
            ]);
        }

        return $commonFields;
    }
}
