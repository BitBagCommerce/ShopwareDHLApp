<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

interface ShipmentFactoryInterface
{
    public function create(
        array $addressStructure,
        array $receiverAddressStructure,
        array $pieceStructure,
        array $customFields,
        array $paymentStructure,
        array $serviceDefinitionStructure
    ): array;
}
