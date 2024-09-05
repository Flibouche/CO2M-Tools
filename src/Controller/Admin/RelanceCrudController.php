<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use App\Entity\Relance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

#[IsGranted('ROLE_ADMIN')]
class RelanceCrudController extends AbstractCrudController
{
    private AdminContextProvider $adminContextProvider;
    protected $mailer;
    private $entityManager;
    private $requestStack;

    public function __construct(AdminContextProvider $adminContextProvider, MailerInterface $mailer, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->adminContextProvider = $adminContextProvider;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return Relance::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function createEntity(string $entityFqcn)
    {
        $relance = new Relance();
        $request = $this->requestStack->getCurrentRequest();

        $factureId = $request->query->get('factureId');

        if ($factureId) {
            $facture = $this->entityManager->getRepository(Facture::class)->find($factureId);
            if ($facture) {
                $relance->setFacture($facture);
            }
        }

        return $relance;
    }

    public function configureFields(string $pageName): iterable
    {
        $context = $this->adminContextProvider->getContext();
        $relance = $context ? $context->getEntity()->getInstance() : null;

        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('clientMail', 'Mail du client')
                ->setFormTypeOption('disabled', false),
            AssociationField::new('facture', 'Facture n°')
                ->setFormTypeOption('choice_label', function ($facture) {
                    $dateEnvoi = $facture->getDateEnvoi();
                    $formattedDate = $dateEnvoi ? $dateEnvoi->format('d/m/Y') : 'Date non définie';
                    return sprintf(
                        'Facture #%d - Client: %s - Envoyée le: %s',
                        $facture->getId(),
                        $facture->getClient(),
                        $formattedDate
                    );
                })
                ->setFormTypeOption('disabled', true),
            DateField::new('dateRelance', 'Date de relance'),
            ChoiceField::new('typeRelance', 'Type de relance'),
            AssociationField::new('mail', 'Template de mail')
                ->setFormTypeOption('choice_label', 'objet')
                ->setFormTypeOption('attr', ['data-mail-message' => '1']),
            TextField::new('objet'),
            TextEditorField::new('message')
                ->setTemplatePath('admin/fields/text_editor.html.twig'),
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

    public function configureAssets(Assets $assets): Assets
    {
        return parent::configureAssets($assets)
            ->addCssFile('styles/app.css')
            ->addJsFile('js/relance.js');
    }
}
