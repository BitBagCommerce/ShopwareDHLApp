<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use BitBag\ShopwareDHLApp\Model\OrderDataInterface;

final class PackageFactory implements PackageFactoryInterface
{
    private AddressFactoryInterface $senderAddressFactory;

    private ReceiverAddressFactoryInterface $receiverAddressFactory;

    private PieceFactoryInterface $packageDetailsFactory;

    private ShipmentFullDataFactoryInterface $shipmentFactory;

    private PaymentDataFactoryInterface $paymentFactory;

    private ServiceDefinitionFactoryInterface $serviceDefinitionFactory;

    public function __construct(
        AddressFactoryInterface $senderAddressFactory,
        ReceiverAddressFactoryInterface $receiverAddressFactory,
        PieceFactoryInterface $packageDetailsFactory,
        ShipmentFullDataFactoryInterface $shipmentFactory,
        PaymentDataFactoryInterface $paymentFactory,
        ServiceDefinitionFactoryInterface $serviceDefinitionFactory
    ) {
        $this->senderAddressFactory = $senderAddressFactory;
        $this->receiverAddressFactory = $receiverAddressFactory;
        $this->packageDetailsFactory = $packageDetailsFactory;
        $this->shipmentFactory = $shipmentFactory;
        $this->paymentFactory = $paymentFactory;
        $this->serviceDefinitionFactory = $serviceDefinitionFactory;
    }

    public function create(ConfigInterface $config, OrderDataInterface $orderData): array
    {
        $addressStructure = $this->senderAddressFactory->create($config);

        $receiverAddressStructure = $this->receiverAddressFactory->create(
            $orderData->getShippingAddress(),
            $orderData->getCustomerEmail(),
            $orderData->getCustomFields()
        );

        $pieceStructure = $this->packageDetailsFactory->create($orderData->getCustomFields(), $orderData->getTotalWeight());

        $paymentStructure = $this->paymentFactory->create($config);

        $serviceDefinitionStructure = $this->serviceDefinitionFactory->create($orderData->getCustomFields());

        return $this->shipmentFactory->create(
            $addressStructure,
            $receiverAddressStructure,
            $pieceStructure,
            $orderData->getCustomFields(),
            $paymentStructure,
            $serviceDefinitionStructure
        );
    }
}
