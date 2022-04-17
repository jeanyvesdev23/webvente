<?php

namespace App\Form;

use App\Entity\Addres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options["user"];
        $builder
            ->add('Address', EntityType::class, [
                'class' => Addres::class,
                'choices' => $user->getAddres(),
                'multiple' => false,
                'expanded' => true,
                'label' => "Addresse de livraison"

            ])
            ->add('Information', TextareaType::class, [
                'label' => 'Information pour lr livreure'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "user" => array()
        ]);
    }
}
