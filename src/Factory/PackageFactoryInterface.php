<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Model\OrderDataInterface;

interface PackageFactoryInterface
{
    public function create(ConfigInterface $config, OrderDataInterface $orderData): array;
}
