<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UlidType;
use Symfony\Component\Form\Extension\Core\Type\WeekType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPro', null, [
                "label" => "Nom du produit"
            ])
            ->add('prixPro', null, [
                "label" => "Prix du produit"
            ])
            ->add('categorie', null, [
                "label" => "Sélectioné le catégorie"
            ])
            ->add('marque', null, [
                "label" => "Sélectioné le marque"
            ])
            ->add('status', null, [
                "label" => "Publier"
            ])
            ->add('nouveau', null, [
                "label" => "Nouveau"
            ])
            ->add('meilleur', null, [
                "label" => "Meilleur"
            ])
            ->add('isFutur', null, [
                "label" => "Futur"
            ])
            ->add('imagePro', FileType::class, [
                "label" => "Image (png ou jpg) file",
                "mapped" => false,
                "required" => false
            ])
            ->add('stock', null, [
                "label" => "Nombre d'articles en stock"
            ])
            ->add('descriptionPro', null, [
                "label" => "Descrition pour le produit"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
