<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

interface ReceiverAddressFactoryInterface
{
    public function create(
        array $shippingAddress,
        string $customerEmail,
        array $customFields
    ): array;
}
