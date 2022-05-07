<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\SearchProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('Categorie', EntityType::class, [
                'class' => Categorie::class,
                "required" => false,
                'multiple' => false,
                'label' => false

            ])
            ->add('minPrix', IntegerType::class, [
                'label' => false,
                "attr" => ["placeholder" => "Min..."]
            ])
            ->add('maxPrix', IntegerType::class, [
                'label' => false,
                "attr" => ["placeholder" => "Max..."]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => SearchProduct::class
        ]);
    }
}
