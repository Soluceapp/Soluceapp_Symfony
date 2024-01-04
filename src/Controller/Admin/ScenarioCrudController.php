<?php

namespace App\Controller\Admin;

use App\Entity\ClassStudent;
use App\Entity\Activity;
use App\Entity\Scenario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ScenarioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
  
        return Scenario::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Scenario')
            ->setEntityLabelInPlural('Scenarios');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('NameScenario'),
            TextField::new('question1')->hideOnIndex(),
            TextField::new('question2')->hideOnIndex(),
            TextField::new('question3')->hideOnIndex(),
            TextField::new('question4')->hideOnIndex(),
            TextField::new('question5')->hideOnIndex(),
            TextField::new('question6')->hideOnIndex(),
            ArrayField::new('reponse1')->hideOnIndex(),
            ArrayField::new('reponse2')->hideOnIndex(),
            ArrayField::new('reponse3')->hideOnIndex(),
            ArrayField::new('reponse4')->hideOnIndex(),
            ArrayField::new('reponse5')->hideOnIndex(),
            ArrayField::new('reponse6')->hideOnIndex(),

        ];
    }

}
