<?php

namespace App\Controller\Admin;

use App\Entity\Relance;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class RelanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Relance::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('facture'),
            DateField::new('dateRelance'),
        ];
    }
}
