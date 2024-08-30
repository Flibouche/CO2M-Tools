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
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

#[IsGranted('ROLE_ADMIN')]
class FactureCrudController extends AbstractCrudController
{
    // AdminContextProvider me permet de récupérer l'instance de l'entité actuellement affichée, je l'injecte donc dans le constructeur
    private AdminContextProvider $adminContextProvider;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminContextProvider $adminContextProvider, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminContextProvider = $adminContextProvider;
        $this->adminUrlGenerator = $adminUrlGenerator;
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
        $context = $this->adminContextProvider->getContext();
        $facture = $context ? $context->getEntity()->getInstance() : null;

        $commonFields = [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('client'),
            DateField::new('dateFacturation', 'Date de facturation')
                ->setRequired(false)
                ->setFormTypeOption('html5', false)
                ->setFormTypeOption('widget', 'single_text'),
            BooleanField::new('genere', 'Généré'),
            BooleanField::new('paye', 'Payé'),
            DateField::new('dateEnvoi', 'Date d\'envoi'),
        ];

        // Si on est sur la page d'index, j'ajoute un champ CollectionField pour afficher le nombre de relances
        if ($pageName === Crud::PAGE_INDEX) {
            $commonFields[] = CollectionField::new('relances')
                ->formatValue(function ($value, $entity) {
                    // Ne pas afficher le nombre de relances si la facture est payée
                    $url = $this->adminUrlGenerator
                        ->setController(RelanceCrudController::class)
                        ->setAction(Crud::PAGE_NEW)
                        ->set('factureId', $entity->getId()) // Passer l'ID de la facture dans l'URL
                        ->generateUrl();

                    return $entity->isPaye()
                        ? 'Null ou payé'
                        : count($value) . ' <a href="' . $url . '">Ajouter une relance</a>';
                });
        }

        // Si la facture actuellement affichée a des relances, j'ajoute un champ CollectionField pour afficher les relances
        if ($pageName === Crud::PAGE_DETAIL && $facture && $facture->getRelances()->count() > 0) {
            $commonFields[] = CollectionField::new('relances')
                ->setEntryIsComplex(true)
                ->onlyOnDetail()
                ->setLabel('Relances')
                ->setTemplatePath('admin/relances_list.html.twig');
        }

        return $commonFields;
    }
}
