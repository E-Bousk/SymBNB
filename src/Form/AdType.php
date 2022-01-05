<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ConfigType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration('Titre :', 'Donnez un titre à votre annonce')
            )
            // ->add(
            //     'slug',
            //     TextType::class,
            //     $this->getConfiguration('Adresse web', 'Indiquez l\'adresse web (automatique)', [
            //         'required' => false
            //     ])
            // )
            ->add(
                'coverImage',
                UrlType::class,
                $this->getConfiguration('URL de l\'image principale :', 'Indiquez l\'URL de votre image principale', [
                    'required' => false,
                    'empty_data' => 'https://picsum.photos/1000/350',
                    'default_protocol' => null
                ])
            )
            ->add(
                'introduction',
                TextType::class,
                $this->getConfiguration('Introduction :', 'Donnez un description sommaire pour votre annonce')
            )
            ->add(
                'content',
                TextareaType::class,
                $this->getConfiguration('Description détaillée :', 'Donnez une description détaillée pour votre annonce')
            )
            ->add(
                'rooms',
                IntegerType::class,
                $this->getConfiguration('Nombre de chambre :', 'Indiquez le nombre de chambre disponible')
            )
            ->add(
                'price',
                MoneyType::class,
                $this->getConfiguration('Prix par nuit :', 'Indiquez votre prix pour une nuit')
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'label' => 'Images :',
                    
                    'entry_type' => ImageType::class,

                    // Crée un « data-prototype »
                    'allow_add' => true,

                    // Permet d'effacer une entrée de la collection
                    'allow_delete' => true
                ]

            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
