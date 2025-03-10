<?php

namespace App\Controller\Admin;

use App\Entity\ClassStudent;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ClassStudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
  
        return ClassStudent::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Classe')
            ->setEntityLabelInPlural('Classes');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('name_class')->setLabel('Niveau'),
            
            BooleanField::new('acces_chevaux'),
            BooleanField::new('acces_compta'),
            BooleanField::new('acces_facture'),
            BooleanField::new('acces_motcroise','Accès mots-croisés'),
            BooleanField::new('acces_flashcardeco'),
            BooleanField::new('acces_flashcardgestion'),
            BooleanField::new('acces_eval_flashcard'),
            IntegerField::new('moyenne_activity'),
        ];
    }

}
