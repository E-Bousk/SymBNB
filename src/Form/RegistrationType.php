<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ConfigType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration('Prénom', 'Votre prénom ...'))
            ->add('lastName', TextType::class, $this->getConfiguration('Nom', 'Votre nom ...'))
            ->add('email', EmailType::class, $this->getConfiguration('Email', 'Votre adresse email ...'))
            ->add('picture', UrlType::class, $this->getConfiguration('Photo de profil', 'URL de votre avatar ...', [
                'default_protocol' => null
            ]))
            ->add('password', PasswordType::class, $this->getConfiguration('Mot de passe', 'Votre mot de passe ...'))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration('Confirmation du mot de passe', 'Confirmez votre mot de passe ...'))
            ->add('introduction', TextType::class,$this->getConfiguration('Introduction', 'Presentez-vous en quelques mots ...'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description détaillée','Présentez-vous ...'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
