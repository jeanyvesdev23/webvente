<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options["emailAndRole"]) {
            $builder->add("email", EmailType::class, [
                "label" => false
            ]);
        } else {

            $builder
                ->add('imageUser', FileType::class, [
                    "label" => false,
                    "mapped" => false,
                    "required" => false
                ])
                ->add('nomUser')
                ->add('prenomUser')
                ->add('email')
                ->add('numberPhone');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'emailAndRole' => false
        ]);
    }
}
