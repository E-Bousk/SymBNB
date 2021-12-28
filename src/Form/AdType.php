<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    /**
     * Configuration de base d'un champ
     * 
     * @param string $label 
     * @param string $placeholder 
     * @return array 
     */
    private function getConfiguration($label, $placeholder) {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
            ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', 'Donnez un titre à votre annonce'))
            ->add('coverImage', UrlType::class, $this->getConfiguration('URL de l\'image principale', 'Indiquez l\'URL de votre image principale'))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', 'Donnez un description sommaire pour votre annonce'))
            ->add('content', TextareaType::class, $this->getConfiguration('Description détaillée', 'Donnez une description détaillée pour votre annonce'))
            ->add('rooms', IntegerType::class, $this->getConfiguration('Nombre de chambre', 'Indiquez le nombre de chambre disponible'))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix par nuit', 'Indiquez votre prix pour une nuit'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
