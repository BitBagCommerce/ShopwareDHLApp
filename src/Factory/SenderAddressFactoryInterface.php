<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;

interface SenderAddressFactoryInterface
{
    public function create(ConfigInterface $config): array;
}
