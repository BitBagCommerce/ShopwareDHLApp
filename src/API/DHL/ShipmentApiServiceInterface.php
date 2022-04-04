<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Model\OrderDataInterface;

interface ShipmentApiServiceInterface
{
    public function createShipments(
        OrderDataInterface $orderData,
        ConfigInterface $config
    ): void;
}
