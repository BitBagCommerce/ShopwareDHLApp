<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use BitBag\ShopwareDHLApp\Entity\ConfigInterface;

interface AddressFactoryInterface
{
    public function create(ConfigInterface $config): array;
}
