<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statusCommandes', null, [
                "label" => false,
                "attr" => [
                    "style" => "width:200px;margin-bottom:-1rem"

                ]
            ])
            ->add('statusPaiement', null, [
                "label" => false,
                "attr" => [
                    "style" => "width:200px;margin-bottom:-1rem"

                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
