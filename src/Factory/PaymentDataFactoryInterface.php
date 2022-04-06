<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use BitBag\ShopwareDHLApp\Entity\ConfigInterface;

interface PaymentDataFactoryInterface
{
    public function create(ConfigInterface $config): array;
}
