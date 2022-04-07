<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use BitBag\ShopwareDHLApp\Model\OrderDataInterface;

interface ShipmentApiServiceInterface
{
    public function createShipments(
        OrderDataInterface $orderData,
        ConfigInterface $config
    ): array;
}
