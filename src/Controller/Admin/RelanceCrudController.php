<?php

namespace App\Controller\Admin;

use App\Entity\Relance;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

#[IsGranted('ROLE_ADMIN')]
class RelanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Relance::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('facture')
                ->setFormTypeOption('choice_label', function ($facture) {
                    $dateEnvoi = $facture->getDateEnvoi();
                    $formattedDate = $dateEnvoi ? $dateEnvoi->format('d/m/Y') : 'Date non définie';
                    return sprintf(
                        'Facture #%d - Client: %s - Envoyée le: %s',
                        $facture->getId(),
                        $facture->getClient(),
                        $formattedDate
                    );
                }),
            DateField::new('dateRelance'),
            ChoiceField::new('typeRelance'),
            AssociationField::new('mail')
                ->setFormTypeOption('choice_label', 'objet'),
            TextField::new('message'),
        ];
    }
}
