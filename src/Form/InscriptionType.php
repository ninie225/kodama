<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email as EmailAssert;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'attr' => [
                    'placeholder' => 'Entrez votre email',
                    'class' => 'form-control',
                    'autocomplete' => 'email',
                    'inputmode'    => 'email',
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir une adresse e-mail.'),
                    new EmailAssert(message: 'Adresse e-mail invalide.', mode: EmailAssert::VALIDATION_MODE_STRICT),
                ],
                'error_bubbling' => false, 
                'trim' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
