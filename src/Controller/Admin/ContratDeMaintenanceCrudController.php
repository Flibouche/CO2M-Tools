<?php

namespace App\Controller\Admin;

use App\Entity\ContratDeMaintenance;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContratDeMaintenanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContratDeMaintenance::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('client'),
            DateField::new('dateDebut'),
            DateField::new('dateFin'),
            ChoiceField::new('tarification')
        ];
    }
}
