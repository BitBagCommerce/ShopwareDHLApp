<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

interface ShipmentSenderInterface
{
    public function createShipments(
        array $orderData
    ): void;
}
