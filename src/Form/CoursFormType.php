<?php

namespace App\Form;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Scenario;
use App\Entity\ClassStudent;
use App\Entity\Dutil;
use App\Repository\DutilRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class CoursFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        
        ->add('Nbscenario', EntityType::class,['attr'=> ['class' =>'style26'],'label' =>' ','class'=>Scenario::class,
       // 'choice'=>function(Scenario $classe)  {  return $classe->getId() . ' - ' . $classe->getNameScenario() ;},
        'choice_label'=>function(Scenario $classe)
        { 
            if($classe->getId()>2)
            return $classe->getId() . ' - ' . $classe->getNameScenario();
        
        },])
 
        ->add('submit', SubmitType::class,['attr'=> ['class' =>'btn btn-primary btn-lg'],'label' =>'Valider'])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Scenario::class,
        ]);
    }
}

