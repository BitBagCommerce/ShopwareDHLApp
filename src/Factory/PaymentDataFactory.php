<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use Alexcherniatin\DHL\Exceptions\InvalidStructureException;
use Alexcherniatin\DHL\Structures\PaymentData;
use BitBag\ShopwareDHLApp\Entity\ConfigInterface;

final class PaymentDataFactory implements PaymentDataFactoryInterface
{
    /**
     * @throws InvalidStructureException
     */
    public function create(ConfigInterface $config): array
    {
        return (new PaymentData())
            ->setPaymentMethod($config->getPaymentMethod())
            ->setPayerType($config->getPayerType())
            ->setAccountNumber($config->getAccountNumber())
            ->structure();
    }
}
