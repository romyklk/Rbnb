<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastName',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('email',EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre email'
                ]
            ])
            ->add('subject',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Objet du message'
                ]
            ])
            ->add('message',TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre message'
                ]
            ])
            ->add('submit',SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
