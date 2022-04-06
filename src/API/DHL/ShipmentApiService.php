<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use Alexcherniatin\DHL\Exceptions\SoapException;
use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use BitBag\ShopwareDHLApp\Exception\ShipmentException;
use BitBag\ShopwareDHLApp\Factory\PackageFactory;
use BitBag\ShopwareDHLApp\Model\OrderDataInterface;

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
