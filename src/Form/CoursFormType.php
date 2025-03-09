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
use Symfony\Component\Security\Core\User\UserInterface;

class CoursFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        
        ->add('Nbscenario', EntityType::class,['attr'=> ['class' =>'style26'],'label' =>' ','class'=>Scenario::class,
        'choice_label'=>function(Scenario $classe){ return $classe->getId() . ' - ' . $classe->getNameScenario(); },
        'query_builder' => function (EntityRepository $er) : QueryBuilder {
             
            return $er->createQueryBuilder('s')
            ->orderBy('s.nbscenario', 'ASC')
            ->where('s.id_classe = :truc')
            ->setParameter('truc',3);
        }])
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
//->where('s.id_classe = :truc')->setParameter('truc',ClassStudent::class);
