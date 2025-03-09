<?php
namespace App\Form;

use App\Entity\ClassStudent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClasseSelectionType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('classe', EntityType::class, [
                    'class' => ClassStudent::class, // Entité associée
                    'choice_label' => 'NameClass', // Nom de la propriété affichée dans la liste déroulante
                    'placeholder' => 'Choisissez un niveau', // Texte par défaut
                    'required' => true,
                    'label'=> false
                ])
                ->add('submit', SubmitType::class, [
                    'attr' => ['class' => 'btn btn-primary btn-lg'],
                    'label' => 'Valider',
                ]);
        }
    
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => null, // Pas de classe spécifique liée au formulaire
            ]);
        }
}
    

