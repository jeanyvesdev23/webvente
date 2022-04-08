<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPro')
            ->add('prixPro')
            ->add('status')
            ->add('nouveau')
            ->add('meilleur')
            ->add('imagePro', FileType::class, [
                "label" => "Image (png ou jpg) file",
                "mapped" => false,
                "required" => false
            ])
            ->add('descriptionPro')
            ->add('categorie')
            ->add('marque');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
