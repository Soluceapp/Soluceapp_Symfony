<?php

namespace App\Form;

use App\Entity\Dutil;
use App\Entity\ClassStudent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,['attr'=> ['class' =>'style26'], 'label' =>'Votre E-mail'])
            ->add('nom', TextType::class,['attr'=> ['class' =>'style26'],'label' =>'Votre nom'])
            ->add('prenom', TextType::class,['attr'=> ['class' =>'style26'],'label' =>'Votre prénom'])
            ->add('pseudo', TextType::class,['attr'=> ['class' =>'style26'], 'label' =>'Votre pseudo'])
            ->add('classe', EntityType::class,['attr'=> ['class' =>'style26'],'label' =>'Votre classe','class'=>ClassStudent::class, 'choice_label'=>'NameClass'])
            ->add('LuLesMentions', CheckboxType::class, [
                'attr'=> ['class' =>'style26'],
                'label' =>"J'ai lu les mentions",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter vos droits RGPD.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password','class' =>'style26'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe.',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit être au moins de {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dutil::class,
        ]);
    }
}
