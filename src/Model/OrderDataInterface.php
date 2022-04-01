<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Model;

interface OrderDataInterface
{
    public function getShippingAddress(): array;

    public function getCustomerEmail(): string;

    public function getTotalWeight(): int;

    public function getCustomFields(): array;

    public function getShopId(): string;
}
