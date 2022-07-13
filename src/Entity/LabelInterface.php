<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Entity;

use BitBag\ShopwareAppSystemBundle\Entity\ShopInterface;

interface LabelInterface
{
    public function getId(): int;

    public function getOrderId(): string;

    public function getParcelId(): string;

    public function getShop(): ShopInterface;

    public function getSalesChannelId(): string;

    public function setSalesChannelId(string $salesChannelId): void;
}
