<?php

namespace App\Form;

use App\Entity\Dutil;
use App\Entity\ClassStudent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class ChangepointsFormType extends AbstractType
{
   

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
                
        $builder
        ->add('Nom', EntityType::class,['attr'=> ['class' =>'style26'],'label' =>' ','class'=>Dutil::class, 
        'choice_label'=>function(Dutil $dutil){  return $dutil->getPrenom() . '  ' . $dutil->getNom() . ' : ' . $dutil->getPoints() . ' points'  ; },
        'query_builder' => function (EntityRepository $er) use ($options) : QueryBuilder  {
            return $er->createQueryBuilder('c')
            ->OrderBy('c.Prenom', 'ASC')
            ->andWhere('c.id_domain = :transmet')
            ->setParameter('transmet',$options['id_domain'])
            ->andWhere('c.classe = :transmet2')
            ->setParameter('transmet2',$options['id_classe']) 
            ;
        }
     
        ])
        
        ->add('points', ChoiceType::class,['attr'=> ['class' =>'style26'],'label' =>'Variation de points ','choices'  => ['+1' => 1, '+2' => 2, '-1' => -1 ]])
        ->add('submit', SubmitType::class,['attr'=> ['class' =>'btn btn-primary btn-lg'],'label' =>'Confirmer'])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(array(
            'id_classe',
            'id_domain'
        ));

        $resolver->setDefaults([
            'data_class' => Dutil::class,

        ]);
    }
}
