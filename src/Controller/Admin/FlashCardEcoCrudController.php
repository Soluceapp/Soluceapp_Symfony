<?php

namespace App\Controller\Admin;

use App\Entity\FlashCardEco;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
class FlashCardEcoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FlashCardEco::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('une flash card d\'économie')
            ->setEntityLabelInPlural('Les Flash Cards d\'économie');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('rectoeco','Recto : Définition'),
            TextField::new('versoeco','Verso : Concept'),
            AssociationField::new('classe')->setLabel('Niveau'),
        
        ];
    }

}
