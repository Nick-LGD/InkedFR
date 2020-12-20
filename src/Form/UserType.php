<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('avatarFile', VichImageType::class, [
                'required' => false,
                'download_uri' => false,
                'allow_delete'=> false,
                'asset_helper' => true,
            ])
            ->add('username')
            ->add('email')
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('mobile');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
