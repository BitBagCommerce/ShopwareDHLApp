<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use BitBag\ShopwareDHLApp\Factory\ShippingMethodPayloadFactory;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Criteria;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

class ShippingMethodPayloadFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $ruleId = 'c5933f809d3f4d0ea5ce8646b844da92';

        $criteria = new Criteria();
        $context = $this->createMock(Context::class);

        $deliveryTime = new IdSearchResult(1, ['1234567'], $criteria, $context);

        $shippingMethodPayloadFactory = new ShippingMethodPayloadFactory();
        $shippingMethod = $shippingMethodPayloadFactory->create($ruleId, $deliveryTime);

        self::assertSame(
            [
                'deliveryTimeId' => $shippingMethod['deliveryTimeId'],
                'name' => $shippingMethod['name'],
                'active' => $shippingMethod['active'],
                'description' => $shippingMethod['description'],
                'taxType' => $shippingMethod['taxType'],
                'translated' => [
                    'name' => $shippingMethod['translated']['name'],
                ],
                'availabilityRuleId' => $shippingMethod['availabilityRuleId'],
                'createdAt' => $shippingMethod['createdAt'],
            ],
            $shippingMethod
        );
    }
}
