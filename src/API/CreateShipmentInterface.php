<?php

namespace BitBag\ShopwareAppSkeleton\API;

interface CreateShipmentInterface
{
    public function createShipments($shippingAddress, $shopId, $customerEmail);
}
