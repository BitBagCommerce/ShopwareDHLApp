<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Model;

use Vin\ShopwareSdk\Data\Entity\OrderAddress\OrderAddressEntity;

interface OrderDataInterface
{
    public function getShippingAddress(): OrderAddressEntity;

    public function getCustomerEmail(): string;

    public function getTotalWeight(): float;

    public function getCustomFields(): array;

    public function getShopId(): string;

    public function getOrderId(): string;
}
