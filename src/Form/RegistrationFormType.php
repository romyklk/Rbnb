<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre email',
                    'class' => 'form-control mb-3',
                ]
            ])
            ->add('firstName',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre prénom',
                    'class' => 'form-control mb-3',
                ]
            ])
            ->add('lastName',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre nom',
                    'class' => 'form-control mb-3',
                ]
            ])
            ->add('address',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre adresse',
                    'class' => 'form-control mb-3',
                ]
            ])
            ->add('city',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre ville',
                    'class' => 'form-control mb-3',
                ]
            ])
            ->add('postalCode',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre code postal',
                    'class' => 'form-control mb-3',
                ]
            ])
            ->add('country',CountryType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre pays',
                    'class' => 'form-control mb-3',
                ],
                'preferred_choices' => ['FR', 'BE', 'CH', 'LU'],
            ])
            ->add('phone',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone',
                    'class' => 'form-control mb-3',
                ]
            ])
            ->add('introduction',TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez une courte introduction',
                    'class' => 'form-control mb-3',
                    'rows' => 3,
                ]
            ])
            ->add('presentation',TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Faites une présentation de vous même et de votre activité !',
                    'class' => 'form-control mb-3',
                    'rows' => 5,
                ]
            ])
            ->add('profilPicture',FileType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre avatar',
                    'class' => 'form-control mb-3',
                ],
                'required' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Entrez votre mot de passe',
                        'class' => 'form-control mb-3',
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe',
                        'class' => 'form-control mb-3',
                    ]
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
