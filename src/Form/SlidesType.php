<?php

namespace App\Form;

use App\Entity\Slides;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class SlidesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('sousTitre')
            ->add('imageSlides', FileType::class, [
                "label" => "Image (png ou jpg) file",
                "mapped" => false,
                "required" => false,
                "constraints" => [
                    new File([
                        "mimeTypes" => [
                            'image/pjpeg',
                            'image/png'
                        ],
                        "mimeTypesMessage" => "Le fichier doit être un type png , jpg ou jpeg"
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slides::class,
        ]);
    }
}
