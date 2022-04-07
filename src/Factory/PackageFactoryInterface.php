<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use BitBag\ShopwareDHLApp\Model\OrderDataInterface;

interface PackageFactoryInterface
{
    public function create(ConfigInterface $config, OrderDataInterface $orderData): array;
}
