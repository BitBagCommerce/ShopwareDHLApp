<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Saver;

interface LabelSaverInterface
{
    public function save(
        string $shopId,
        int $shipmentId,
        string $orderId
    ): void;
}
