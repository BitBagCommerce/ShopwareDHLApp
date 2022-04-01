<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Persister;

interface LabelPersisterInterface
{
    public function persist(
        string $shopId,
        int $shipmentId,
        string $orderId
    ): void;
}
