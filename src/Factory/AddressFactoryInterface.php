<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;

interface AddressFactoryInterface
{
    public function create(ConfigInterface $config): array;
}
