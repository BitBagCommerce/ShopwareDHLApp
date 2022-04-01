<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Entity;

interface LabelInterface
{
    public function getId(): int;

    public function getOrderId(): string;

    public function getParcelId(): string;

    public function getShop(): ShopInterface;
}
