<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use Alexcherniatin\DHL\Exceptions\SoapException;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Exception\ShipmentException;
use BitBag\ShopwareAppSkeleton\Factory\AddressFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\PaymentDataFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\PieceFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\ReceiverAddressFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\ServiceDefinitionFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\ShipmentFullDataFactoryInterface;

final class ShipmentSender implements ShipmentSenderInterface
{
    private ApiResolverInterface $apiResolver;

    private AddressFactoryInterface $senderAddressFactory;

    private ReceiverAddressFactoryInterface $receiverAddressFactory;

    private PieceFactoryInterface $packageDetailsFactory;

    private ShipmentFullDataFactoryInterface $shipmentFactory;

    private PaymentDataFactoryInterface $paymentFactory;

    private ServiceDefinitionFactoryInterface $serviceDefinitionFactory;

    public function __construct(
        ApiResolverInterface $apiResolver,
        AddressFactoryInterface $senderAddressFactory,
        ReceiverAddressFactoryInterface $receiverAddressFactory,
        PieceFactoryInterface $packageDetailsFactory,
        ShipmentFullDataFactoryInterface $shipmentFactory,
        PaymentDataFactoryInterface $paymentFactory,
        ServiceDefinitionFactoryInterface $serviceDefinitionFactory
    ) {
        $this->apiResolver = $apiResolver;
        $this->senderAddressFactory = $senderAddressFactory;
        $this->receiverAddressFactory = $receiverAddressFactory;
        $this->packageDetailsFactory = $packageDetailsFactory;
        $this->shipmentFactory = $shipmentFactory;
        $this->paymentFactory = $paymentFactory;
        $this->serviceDefinitionFactory = $serviceDefinitionFactory;
    }

    public function createShipments(
        array $orderData,
        ConfigInterface $config
    ): void {
        $dhl = $this->apiResolver->getApi($orderData['shopId']);

        $addressStructure = $this->senderAddressFactory->create($config);

        $receiverAddressStructure = $this->receiverAddressFactory->create($orderData['shippingAddress'], $orderData['customerEmail'], $orderData['customFields']);

        $pieceStructure = $this->packageDetailsFactory->create($orderData['customFields'], $orderData['totalWeight']);

        $paymentStructure = $this->paymentFactory->create($config);

        $serviceDefinitionStructure = $this->serviceDefinitionFactory->create($orderData['customFields']);

        $shipmentFullDataStructure = $this->shipmentFactory->create(
            $addressStructure,
            $receiverAddressStructure,
            $pieceStructure,
            $orderData['customFields'],
            $paymentStructure,
            $serviceDefinitionStructure
        );

        try {
            $dhl->createShipments($shipmentFullDataStructure);
        } catch (SoapException $th) {
            throw new ShipmentException($th->getMessage());
        }
    }
}
