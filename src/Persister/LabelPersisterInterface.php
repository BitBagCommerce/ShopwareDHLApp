<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Persister;

interface LabelPersisterInterface
{
    public function persist(
        string $shopId,
        int $shipmentId,
        string $orderId,
        string $salesChannelId,
        ): void;
}
