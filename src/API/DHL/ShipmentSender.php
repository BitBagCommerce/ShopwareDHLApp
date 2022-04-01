<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use Alexcherniatin\DHL\Exceptions\SoapException;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Exception\ShipmentException;
use BitBag\ShopwareAppSkeleton\Factory\PackageFactory;
use BitBag\ShopwareAppSkeleton\Model\OrderDataInterface;

final class ShipmentSender implements ShipmentSenderInterface
{
    private ApiResolverInterface $apiResolver;

    private PackageFactory $packageFactory;

    public function __construct(
        ApiResolverInterface $apiResolver,
        PackageFactory $packageFactory
    ) {
        $this->apiResolver = $apiResolver;
        $this->packageFactory = $packageFactory;
    }

    public function createShipments(
        OrderDataInterface $orderData,
        ConfigInterface $config
    ): void {
        $dhl = $this->apiResolver->getApi($orderData->getShopId());

        $shipmentFullDataStructure = $this->packageFactory->create($config, $orderData);

        try {
            $dhl->createShipments($shipmentFullDataStructure);
        } catch (SoapException $th) {
            throw new ShipmentException($th->getMessage());
        }
    }
}
