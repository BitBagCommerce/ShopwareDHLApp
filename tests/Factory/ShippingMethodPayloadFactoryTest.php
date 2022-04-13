<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use BitBag\ShopwareDHLApp\Factory\ShippingMethodPayloadFactory;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\AccessToken;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Criteria;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

class ShippingMethodPayloadFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $ruleId = 'c5933f809d3f4d0ea5ce8646b844da92';

        $accessToken = new AccessToken('123');
        $context = new Context('', $accessToken);
        $criteria = new Criteria();

        $deliveryTime = new IdSearchResult(1, ['1234567'], $criteria, $context);

        $shippingMethodPayloadFactory = new ShippingMethodPayloadFactory();
        $shippingMethod = $shippingMethodPayloadFactory->create($ruleId, $deliveryTime);

        $this->assertSame(
            [
                'deliveryTimeId' => '1234567',
                'name' => 'DHL',
                'active' => true,
                'description' => 'DHL',
                'taxType' => 'auto',
                'translated' => [
                    'name' => 'DHL',
                ],
                'availabilityRuleId' => 'c5933f809d3f4d0ea5ce8646b844da92',
                'createdAt' => $shippingMethod['createdAt'],
            ],
            $shippingMethod
        );
    }
}
