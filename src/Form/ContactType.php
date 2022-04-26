<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => false,
                "attr" => ["placeholder" => "Votre nom"]
            ])
            ->add('email', TextType::class, [
                "label" => false,
                "attr" => ["placeholder" => "Votre address email"]
            ])
            ->add('subject', TextType::class, [
                "label" => false,
                "attr" => ["placeholder" => "subject"]
            ])
            ->add('message', TextareaType::class, [
                "label" => false,
                "attr" => ["placeholder" => "Message"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Contact::class
        ]);
    }
}
