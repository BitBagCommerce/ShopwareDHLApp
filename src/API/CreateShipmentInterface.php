<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

interface CreateShipmentInterface
{
    public function createShipments(
        $shippingAddress,
        $shopId,
        $customerEmail,
        $totalWeight
    );
}
