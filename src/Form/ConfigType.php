<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Form;

use BitBag\ShopwareAppSkeleton\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
            ])
            ->add('password', TextType::class, [
                'required' => true,
            ])
            ->add('accountNumber', TextType::class, [
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('postalCode', TextType::class, [
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'required' => true,
            ])
            ->add('street', TextType::class, [
                'required' => true,
            ])
            ->add('houseNumber', TextType::class, [
                'required' => true,
            ])
            ->add('phoneNumber', TextType::class, [
                'required' => true,
            ])
            ->add('email', TextType::class, [
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
