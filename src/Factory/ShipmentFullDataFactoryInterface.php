<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

interface ShipmentFullDataFactoryInterface
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
