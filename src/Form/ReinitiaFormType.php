<?php

namespace App\Form;

use App\Entity\Dutil;
use App\Entity\ClassStudent;
use App\Entity\DomaineStudent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class ReinitiaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('classe', EntityType::class, [
                'attr' => ['class' => 'style26'],
                'label' => 'Niveau',
                'class' => ClassStudent::class,
                'choice_label' => fn(ClassStudent $classe) => $classe->getNameClass(),
            ])
            ->add('id_domain', EntityType::class, [
                'attr' => ['class' => 'style26'],
                'label' => 'Classe',
                'class' => DomaineStudent::class,
                'choice_label' => fn(DomaineStudent $domaine) => $domaine->getNameDomaine(),
            ])
            ->add('context', ChoiceType::class, [
                'attr' => ['class' => 'style26'],
                'label' => 'Points ou notes',
                'choices' => [
                    'Éco' => 'eco',
                    'Gestion' => 'gestion',
                    'Outil de Gestion' => 'outilgestion',
                    'Participation' => 'participation',
                ],
                'mapped' => false, // Le champ n'est pas lié à une entité
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-lg'],
                'label' => 'Réinitialiser',
            ]);
    }
    
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dutil::class,
        ]);
    }
}