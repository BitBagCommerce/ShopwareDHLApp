<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use Alexcherniatin\DHL\Exceptions\SoapException;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Exception\ShipmentException;
use BitBag\ShopwareAppSkeleton\Factory\PackageFactory;
use BitBag\ShopwareAppSkeleton\Model\OrderDataInterface;

final class ShipmentApiService implements ShipmentApiServiceInterface
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
    ): array {
        $dhl = $this->apiResolver->getApi($orderData->getShopId());
        $shipmentFullDataStructure = $this->packageFactory->create($config, $orderData);

        try {
            return $dhl->createShipments($shipmentFullDataStructure);
        } catch (SoapException $e) {
            throw new ShipmentException($e->getMessage());
        }
    }
}
