<?php

namespace App\Form;

use App\Entity\Dutil;
use App\Entity\ClassStudent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChangeclasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('classe', EntityType::class,['attr'=> ['class' =>'style26'],'label' =>'Me passer en','class'=>ClassStudent::class, 
        'choice_label'=>function(ClassStudent $classe){return $classe->getId() . ' - ' . $classe->getNameClass();}])
       // ->add('pseudo', TextType::class,['attr'=> ['class' =>'style26'],'label' =>'Votre pseudo'])
        ->add('submit', SubmitType::class,['attr'=> ['class' =>'btn btn-primary btn-lg'],'label' =>'Valider'])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dutil::class,
        ]);
    }
}
