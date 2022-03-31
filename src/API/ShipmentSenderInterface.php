<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;

interface ShipmentSenderInterface
{
    public function createShipments(
        array $orderData,
        ConfigInterface $config
    ): void;
}
