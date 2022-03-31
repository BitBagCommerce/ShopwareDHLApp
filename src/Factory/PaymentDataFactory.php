<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use Alexcherniatin\DHL\Exceptions\InvalidStructureException;
use Alexcherniatin\DHL\Structures\PaymentData;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;

final class PaymentDataFactory implements PaymentDataFactoryInterface
{
    /**
     * @throws InvalidStructureException
     */
    public function create(ConfigInterface $config): array
    {
        return (new PaymentData())
            ->setPaymentMethod(PaymentData::PAYMENT_METHOD_BANK_TRANSFER)
            ->setPayerType($config->getPayerType())
            ->setAccountNumber($config->getAccountNumber())
            ->structure();
    }
}
