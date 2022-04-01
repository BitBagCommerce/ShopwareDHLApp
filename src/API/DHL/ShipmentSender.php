<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use Alexcherniatin\DHL\Exceptions\SoapException;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Exception\ShipmentException;
use BitBag\ShopwareAppSkeleton\Factory\PackageFactory;
use BitBag\ShopwareAppSkeleton\Model\OrderDataInterface;
use BitBag\ShopwareAppSkeleton\Persister\LabelPersisterInterface;

final class ShipmentSender implements ShipmentSenderInterface
{
    private ApiResolverInterface $apiResolver;

    private PackageFactory $packageFactory;

    private LabelPersisterInterface $labelPersister;

    public function __construct(
        ApiResolverInterface $apiResolver,
        PackageFactory $packageFactory,
        LabelPersisterInterface $labelPersister
    ) {
        $this->apiResolver = $apiResolver;
        $this->packageFactory = $packageFactory;
        $this->labelPersister = $labelPersister;
    }

    public function createShipments(
        OrderDataInterface $orderData,
        ConfigInterface $config
    ): void {
        $dhl = $this->apiResolver->getApi($orderData->getShopId());

        $shipmentFullDataStructure = $this->packageFactory->create($config, $orderData);

        try {
            $shipment = $dhl->createShipments($shipmentFullDataStructure);
            $this->labelPersister->persist($orderData->getShopId(), $shipment['shipmentId'], $orderData->getOrderId());
        } catch (SoapException $th) {
            throw new ShipmentException($th->getMessage());
        }
    }
}
