<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends AbstractType
{

    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('startDate',TextType::class, $this->getConfiguration("Date d'arrivée", "Veuillez saisir votre date d'arrivée..."
            ))

            ->add('createdAt',TextType::class, $this->getConfiguration("Date de départ", "Veuillez saisir votre date de départ.."))

            ->add('comment', TextareaType::class, $this->getConfiguration(false, "Veuillez saisir votre message de réservation..Communiquez votre heure d'arrivée et le motif de votre voyage à votre hôte.", 
            [
                'required' => false,
                
                ]));

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('createdAt')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }

    private function getConfiguration($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
}
