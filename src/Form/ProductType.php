<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameProduct')
            ->add('priceProduct')
            ->add('moreInformation')
            ->add('isBest')
            ->add('isNew')
            ->add('imageProduct', FileType::class, [
                "label" => "Image (Png or Jpg file)",
                "required" => false,
                "mapped" => false
            ])

            ->add('categories');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
