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
    public const DELIVERY_TIME_ID = '1234567';

    public const NAME = 'DHL';

    public const ACTIVE = true;

    public const DESCRIPTION = 'DHL';

    public const TAX_TYPE = 'auto';

    public const RULE_ID = 'c5933f809d3f4d0ea5ce8646b844da92';

    public function testCreate(): void
    {
        $ruleId = 'c5933f809d3f4d0ea5ce8646b844da92';

        $criteria = new Criteria();
        $context = $this->createMock(Context::class);

        $deliveryTime = new IdSearchResult(1, ['1234567'], $criteria, $context);

        $shippingMethodPayloadFactory = new ShippingMethodPayloadFactory();
        $shippingMethod = $shippingMethodPayloadFactory->create($ruleId, $deliveryTime);

        self::assertEquals(
            [
                'deliveryTimeId' => self::DELIVERY_TIME_ID,
                'name' => self::NAME,
                'active' => self::ACTIVE,
                'description' => self::DESCRIPTION,
                'taxType' => self::TAX_TYPE,
                'translated' => [
                    'name' => self::NAME,
                ],
                'availabilityRuleId' => self::RULE_ID,
                'createdAt' => $shippingMethod['createdAt'],
            ],
            $shippingMethod
        );
    }
}
