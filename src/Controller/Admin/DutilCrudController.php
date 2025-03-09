<?php

namespace App\Controller\Admin;

use App\Entity\Dutil;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig\EntityListeners\EntityConfig;

class DutilCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Dutil::class;
    }


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
            TextField::new('email')->hideOnIndex(), //->setFormTypeOption('disabled','disabled')
            TextField::new('pseudo'),
            DateField::new('createdAt'),
            ArrayField::new('roles')->hideOnIndex(),
            BooleanField::new('Is_Verified')->setLabel('Mail vérifié'),
            AssociationField::new('classe')->setLabel('Niveau'),//->setFormTypeOption('disabled', true),
            AssociationField::new('id_domain')->setLabel('Classe'),
            IntegerField::new('points'),
            IntegerField::new('noteevaleco'),
            IntegerField::new('noteevalgestion'),
            IntegerField::new('noteevaloutilgestion'),
            ArrayField::new('scenariofait')->hideOnIndex(),
            ArrayField::new('limparticipation')->hideOnIndex(),
            ArrayField::new('motcroisefait')->hideOnIndex(),
        ];
    }

}
