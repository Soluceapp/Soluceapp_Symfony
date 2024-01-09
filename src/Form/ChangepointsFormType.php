<?php

namespace App\Form;

use App\Entity\Dutil;
use App\Entity\ClassStudent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangepointsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Nom', EntityType::class,['attr'=> ['class' =>'style26'],'label' =>'+1 point','class'=>Dutil::class, 
        'choice_label'=>function(Dutil $dutil){return $dutil->getId() . ' - ' . $dutil->getNom() . ' - ' . $dutil->getPrenom() . ' - ' . $dutil->getPoints();}])
        ->add('submit', SubmitType::class,['attr'=> ['class' =>'btn btn-primary btn-lg'],'label' =>'Confirmer'])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dutil::class,
        ]);
    }
}
