<?php

namespace App\Form;

use App\Form\ConfigType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfiguration('Ancien mot de passe', 'Renseignez votre mot de passe actuel'))
            ->add('newPassword', PasswordType::class, $this->getConfiguration('Nouveau mot de passe', 'Renseignez votre nouveau mot de passe'))
            ->add('confirmPassword', PasswordType::class, $this->getConfiguration('Confirmation du nouveau mot de passe', 'Confirmez votre nouveau mot de passe'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
