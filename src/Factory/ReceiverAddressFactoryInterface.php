<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use Vin\ShopwareSdk\Data\Entity\OrderAddress\OrderAddressEntity;

interface ReceiverAddressFactoryInterface
{
    public function create(
        OrderAddressEntity $shippingAddress,
        string $customerEmail,
        array $customFields
    ): array;
}
