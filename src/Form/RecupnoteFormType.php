<?php

namespace App\Form;

use App\Entity\Dutil;
use App\Entity\ClassStudent;
use App\Entity\DomaineStudent;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecupnoteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('classe', EntityType::class,['attr'=> ['class' =>'style26'],'label' =>'Niveau','class'=>ClassStudent::class, 
            'choice_label'=>function(ClassStudent $classe){return $classe->getId() . ' - ' . $classe->getNameClass();}])
            ->add('id_domain', EntityType::class,['attr'=> ['class' =>'style26'],'label' =>'Classe','class'=>DomaineStudent::class, 
            'choice_label'=>function(DomaineStudent $domaine){return $domaine->getId() . ' - ' . $domaine->getNamedomaine();}]) 
            ->add('submit', SubmitType::class,['attr'=> ['class' =>'btn btn-primary btn-lg'],'label' =>'Valider'])   ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dutil::class,
        ]);
    }
}
