<?php
namespace App\Form;

use App\Entity\Scenario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScenarioSelectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('scenario', EntityType::class, [
                'class' => Scenario::class,
                'choice_label' => 'NameScenario', // Propriété à afficher dans la liste déroulante
                'placeholder' => 'Choisissez un scénario',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}