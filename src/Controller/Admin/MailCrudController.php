<?php

namespace App\Controller\Admin;

use App\Entity\Mail;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mail::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('objet'),
            TextEditorField::new('message')
                ->setTemplatePath('admin/fields/text_editor.html.twig'),
        ];
    }

    public function configureAssets(Assets $assets): Assets
    {
        return parent::configureAssets($assets)
            ->addCssFile('styles/app.css');
    }
}
