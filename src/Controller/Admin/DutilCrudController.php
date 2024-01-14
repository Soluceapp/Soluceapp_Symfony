<?php

namespace App\Controller\Admin;

use App\Entity\Dutil;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig\EntityListeners\EntityConfig;

class DutilCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Dutil::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Nom'),
            TextField::new('Prenom'),
            TextField::new('email')->setFormTypeOption('disabled','disabled')->hideOnIndex(),
            TextField::new('pseudo'),
            ArrayField::new('roles')->hideOnIndex(),
            BooleanField::new('Is_Verified'),
            TextField::new('Classe')->setFormTypeOption('disabled','disabled'),
            ArrayField::new('Id_domain_id')->setFormTypeOption('disabled','disabled'),
            IntegerField::new('Points'),
            ArrayField::new('scenariofait')->hideOnIndex(),
            ArrayField::new('limparticipation')->hideOnIndex(),
            ArrayField::new('motcroisefait')->hideOnIndex(),
        ];
    }

}
