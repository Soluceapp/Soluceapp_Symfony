<?php

namespace App\Controller\Admin;

use App\Entity\FlashCardGestion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class FlashCardGestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FlashCardGestion::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('une flash card de gestion')
            ->setEntityLabelInPlural('Les flash cards de gestion');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('rectogestion','Recto : Définition'),
            TextField::new('versogestion','Verso : Concept'),
            AssociationField::new('classe')->setLabel('Niveau'),
        
        ];
    }

}
