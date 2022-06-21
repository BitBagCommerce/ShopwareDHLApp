<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Form;

use Alexcherniatin\DHL\Structures\PaymentData;
use BitBag\ShopwareDHLApp\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.username',
            ])
            ->add('password', TextType::class, [
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.password',
            ])
            ->add('accountNumber', TextType::class, [
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.account_number',
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.name',
            ])
            ->add('postalCode', TextType::class, [
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.post_code',
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.city',
            ])
            ->add('street', TextType::class, [
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.street',
            ])
            ->add('houseNumber', TextType::class, [
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.house_number',
            ])
            ->add('phoneNumber', TextType::class, [
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.phone_number',
            ])
            ->add('email', TextType::class, [
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.email',
            ])
            ->add('payerType', ChoiceType::class, [
                'choices' => [
                    'bitbag.shopware_dhl_app.config.shipper' => PaymentData::PAYER_TYPE_SHIPPER,
                    'bitbag.shopware_dhl_app.config.receiver' => PaymentData::PAYER_TYPE_RECEIVER,
                ],
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.payer_type',
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'choices' => [
                    'bitbag.shopware_dhl_app.config.bank_transfer' => PaymentData::PAYMENT_METHOD_BANK_TRANSFER,
                    'bitbag.shopware_dhl_app.config.cash' => PaymentData::PAYMENT_METHOD_CASH,
                ],
                'required' => true,
                'label' => 'bitbag.shopware_dhl_app.config.payment_method',
            ])
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                static function (FormEvent $event): void {
                    $data = $event->getData();

                    if (isset($data['postalCode'])) {
                        $data['postalCode'] = str_replace(['-', ' '], '', $data['postalCode']);
                    }

                    $event->setData($data);
                }
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
