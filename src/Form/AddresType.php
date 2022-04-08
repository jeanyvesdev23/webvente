<?php

namespace App\Form;

use App\Entity\Addres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName')
            ->add('numero')
            ->add('pays')
            ->add('province')
            ->add('region')
            ->add('parcelle');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Addres::class,
        ]);
    }
}
