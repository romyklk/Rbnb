<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre de l\'annonce',
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('introduction', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Introduction de l\'annonce',
                    'class' => 'form-control mb-3',
                    'rows' => 3
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Donnez plus de détails sur votre annonce',
                    'class' => 'form-control mb-3',
                    'rows' => 8
                ]
            ])
            ->add('Price', MoneyType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix par nuit',
                    'class' => 'form-control'
                ]
            ])
            ->add('rooms', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nombre de chambres',
                    'class' => 'form-control'
                ],

            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Loft' => 'loft',
                    'Maison' => 'maison',
                    'Appartement' => 'appartement',
                    'Chambre' => 'chambre',
                    'Studio' => 'studio',
                    'Manoir' => 'manoir',
                    'Château' => 'château',
                    'Villa' => 'villa',
                    'Mobil-home' => 'mobil-home',
                    'Maison de maître' => 'maison de maître',
                ],
                'multiple' => false,
            ])

            ->add('adress', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse de l\'annonce',
                    'class' => 'form-control'
                ]
            ])

            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville de l\'annonce',
                    'class' => 'form-control'
                ]
            ])

            ->add('zipCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code postal de l\'annonce',
                    'class' => 'form-control'
                ]
            ])

            ->add('country', CountryType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'preferred_choices'=> ['FR', 'BE', 'LU', 'CH', 'DE', 'IT', 'ES', 'PT', 'GB', 'IE', 'NL', 'AT', 'PL', 'CZ', 'SK', 'HU', 'RO', 'BG', 'GR', 'DK', 'SE', 'NO', 'FI', 'EE', 'LV', 'LT', 'CY', 'MT', 'IS']
                
            ])

            ->add('coverImage', FileType::class, [
                'label' => 'Image de couverture',
                'attr' => [
                    'placeholder' => 'URL de l\'image',
                    'class' => 'form-control'
                ],
                'data_class' => null,
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'label' => 'Images supplémentaires',
                'data_class' => null,
            ])

            ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
