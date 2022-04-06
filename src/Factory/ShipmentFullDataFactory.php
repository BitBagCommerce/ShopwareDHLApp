<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use Alexcherniatin\DHL\Structures\ShipmentFullData;
use BitBag\ShopwareDHLApp\Provider\Defaults;

final class ShipmentFullDataFactory implements ShipmentFullDataFactoryInterface
{
    public function create(
        array $addressStructure,
        array $receiverAddressStructure,
        array $pieceStructure,
        array $customFields,
        array $paymentStructure,
        array $serviceDefinitionStructure
    ): array {
        return (new ShipmentFullData())
            ->setShipper($addressStructure)
            ->setReceiver($receiverAddressStructure)
            ->setPieceList([$pieceStructure])
            ->setPayment($paymentStructure)
            ->setService($serviceDefinitionStructure)
            ->setShipmentDate(\date(ShipmentFullData::DATE_FORMAT, \strtotime($customFields[Defaults::PACKAGE_SHIPPING_DATE])))
            ->setContent($customFields[Defaults::PACKAGE_DESCRIPTION])
            ->setSkipRestrictionCheck(true)
            ->structure();
    }
}
