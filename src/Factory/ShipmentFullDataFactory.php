<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use Alexcherniatin\DHL\Exceptions\InvalidStructureException;
use Alexcherniatin\DHL\Structures\ShipmentFullData;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

final class ShipmentFullDataFactory implements ShipmentFullDataFactoryInterface
{
    /**
     * @throws InvalidStructureException
     */
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
