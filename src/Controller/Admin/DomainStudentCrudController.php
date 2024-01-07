<?php

namespace App\Controller\Admin;

use App\Entity\ClassStudent;
use App\Entity\Dutil;
use App\Entity\DomaineStudent;
use App\Entity\Scenario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class DomainStudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
  
        return DomaineStudent::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('DomainStudent')
            ->setEntityLabelInPlural('DomainStudents');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Namedomaine'),

        ];
    }

}
