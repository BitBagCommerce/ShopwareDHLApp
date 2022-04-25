<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\DHL;

use Alexcherniatin\DHL\DHL24;
use BitBag\ShopwareDHLApp\API\DHL\ApiResolverInterface;
use BitBag\ShopwareDHLApp\API\DHL\ShipmentApiService;
use BitBag\ShopwareDHLApp\Entity\Config;
use BitBag\ShopwareDHLApp\Factory\PackageFactoryInterface;
use BitBag\ShopwareDHLApp\Model\OrderDataInterface;
use PHPUnit\Framework\TestCase;

class ShipmentApiServiceTest extends TestCase
{
    private const SHOP_ID = '123';

    public function testCreateShipments()
    {
        $apiResolver = $this->createMock(ApiResolverInterface::class);
        $packageFactory = $this->createMock(PackageFactoryInterface::class);
        $orderData = $this->createMock(OrderDataInterface::class);
        $dhl = $this->createMock(DHL24::class);

        $config = new Config();

        $orderData->method('getShopId')->willReturn(self::SHOP_ID);

        $apiResolver->expects(self::once())->method('getApi')->with(self::SHOP_ID)->willReturn($dhl);

        $packageFactory->expects(self::once())->method('create')->with($config, $orderData);

        $dhl->expects(self::once())->method('createShipments')->with([]);

        $shipmentApiService = new ShipmentApiService($apiResolver, $packageFactory);

        $shipmentApiService->createShipments($orderData, $config);
    }
}
