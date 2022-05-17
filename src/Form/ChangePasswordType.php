<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('curentPassword', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Entrer votre mot de passe actuel"
                    ]),
                    new UserPassword,
                ],
                'label' => "Votre mot de passe"
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => "Entrer votre nouvellle mot de passe"
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => "Votre mot de passe doivent être {{limit}} caractere",
                            'max' => 4096
                        ]),
                    ],
                    'label' => 'Nouveau mot de passe'
                ],
                'second_options' => [
                    'label' => "Confirmer Votre mot de passe",
                    'mapped' => false
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
