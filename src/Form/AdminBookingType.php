<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('comment', TextareaType::class, [
                'required' => false
            ])
            ->add('booker', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName'
            ])
            ->add('ad', EntityType::class, [
                'class' => Ad::class,
                'choice_label' => function($ad) {
                    return sprintf("Annonce numéro %s - %s", $ad->getId(), strtoupper($ad->getTitle()));
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
