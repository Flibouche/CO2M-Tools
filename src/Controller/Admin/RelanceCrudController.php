<?php

namespace App\Controller\Admin;

use App\Entity\Relance;
use Doctrine\ORM\EntityManagerInterface;
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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

#[IsGranted('ROLE_ADMIN')]
class RelanceCrudController extends AbstractCrudController
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

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

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // if ($entityInstance->getTypeRelance() === 'Mail') {
        $this->sendMail($entityInstance);
        // }

        parent::persistEntity($entityManager, $entityInstance);
    }

    private function sendMail(Relance $relance)
    {
        $email = (new TemplatedEmail())
            ->from('kevin@co2m.fr')
            ->to($relance->getFacture()->getClient()->getMail())
            ->subject($relance->getMail()->getObjet() . $relance->getFacture()->getId())
            ->htmlTemplate('emails/template.html.twig')
            ->context([
                'relance' => $relance,
            ]);

        $this->mailer->send($email);
    }
}
