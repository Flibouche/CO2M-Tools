<?php

namespace App\Controller\Admin;

use App\Entity\Relance;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            ChoiceField::new('typeRelance'),
            TextField::new('message'),
        ];
    }
}
