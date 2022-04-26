<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\DHL;

use Alexcherniatin\DHL\DHL24;
use BitBag\ShopwareDHLApp\API\DHL\ApiResolverInterface;
use BitBag\ShopwareDHLApp\API\DHL\LabelApiService;
use BitBag\ShopwareDHLApp\Model\LabelData;
use PHPUnit\Framework\TestCase;

class LabelApiServiceTest extends TestCase
{
    public const SHOP_ID = 'test123';

    public const PARCEL_ID = '321';

    public const LABEL_TYPE = 'BLP';

    public const SHIPMENT_ID = '321';

    public const LABEL_DATA = 'test123';

    public function testFetchLabel()
    {
        $dhl24 = $this->createMock(DHL24::class);

        $results = [
            'labelType' => self::LABEL_TYPE,
            'shipmentId' => self::SHIPMENT_ID,
            'labelData' => self::LABEL_DATA,
        ];

        $dhl24->method('getLabels')->willReturn($results);

        $apiResolver = $this->createMock(ApiResolverInterface::class);
        $apiResolver->method('getApi')->willReturn($dhl24);

        $labelApiService = new LabelApiService($apiResolver);

        $labelData = new LabelData(self::LABEL_TYPE, self::SHIPMENT_ID, self::LABEL_DATA);

        self::assertEquals($labelData, $labelApiService->fetchLabel(self::PARCEL_ID, self::SHOP_ID));
    }
}
