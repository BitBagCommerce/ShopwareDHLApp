<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Exception\ConfigNotFoundException;
use BitBag\ShopwareAppSkeleton\Factory\PackageDetailsFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\PaymentFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\ReceiverAddressFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\SenderAddressFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\ServiceDefinitionFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\ShipmentFactoryInterface;
use BitBag\ShopwareAppSkeleton\Repository\ConfigRepository;

final class ShipmentSender implements ShipmentSenderInterface
{
    private ConfigRepository $configRepository;

    private ApiServiceInterface $apiService;

    private SenderAddressFactoryInterface $senderAddressFactory;

    private ReceiverAddressFactoryInterface $receiverAddressFactory;

    private PackageDetailsFactoryInterface $packageDetailsFactory;

    private ShipmentFactoryInterface $shipmentFactory;

    private PaymentFactoryInterface $paymentFactory;

    private ServiceDefinitionFactoryInterface $serviceDefinitionFactory;

    public function __construct(
        ConfigRepository $configRepository,
        ApiServiceInterface $apiService,
        SenderAddressFactoryInterface $senderAddressFactory,
        ReceiverAddressFactoryInterface $receiverAddressFactory,
        PackageDetailsFactoryInterface $packageDetailsFactory,
        ShipmentFactoryInterface $shipmentFactory,
        PaymentFactoryInterface $paymentFactory,
        ServiceDefinitionFactoryInterface $serviceDefinitionFactory
    ) {
        $this->configRepository = $configRepository;
        $this->apiService = $apiService;
        $this->senderAddressFactory = $senderAddressFactory;
        $this->receiverAddressFactory = $receiverAddressFactory;
        $this->packageDetailsFactory = $packageDetailsFactory;
        $this->shipmentFactory = $shipmentFactory;
        $this->paymentFactory = $paymentFactory;
        $this->serviceDefinitionFactory = $serviceDefinitionFactory;
    }

    public function createShipments(
        array $orderData
    ): void {
        /** @var ConfigInterface|null $config */
        $config = $this->configRepository->findOneBy(['shop' => $orderData['shopId']]);

        if (null === $config) {
            throw new ConfigNotFoundException('Config not found');
        }

        $dhl = $this->apiService->getApi($orderData['shopId']);

        $addressStructure = $this->senderAddressFactory->create($config);

        $receiverAddressStructure = $this->receiverAddressFactory->create($orderData['shippingAddress'], $orderData['customerEmail']);

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
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
