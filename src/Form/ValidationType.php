<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type'            => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'required'        => true,
                'error_bubbling' => false,
                'first_options'  => [
                    'label'      => 'Mot de passe',
                    'label_attr' => ['class' => 'text-start d-block fw-bold'],
                    'attr'       => [
                        'class'        => 'form-control',
                        'placeholder'  => 'Nouveau mot de passe',
                        'autocomplete' => 'new-password',
                    ],
                    'constraints' => [
                        new NotBlank(message: 'Veuillez saisir un mot de passe.'),
                        new Length(
                            min: 12,
                            minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères.'
                        ),
                    ],
                    'trim' => false,
                ],
                'second_options'  => [
                    'label'      => 'Confirmer le mot de passe',
                    'label_attr' => ['class' => 'text-start d-block fw-bold'],
                    'attr'       => [
                        'class'        => 'form-control',
                        'placeholder'  => 'Confirmez le mot de passe',
                        'autocomplete' => 'new-password',
                    ],
                    'constraints' => [
                        new NotBlank(message: 'Veuillez confirmer votre mot de passe.'),
                    ],
                    'trim' => false,
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr'  => ['class' => 'btn btn-lg btn-outline-success rounded-5 mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
