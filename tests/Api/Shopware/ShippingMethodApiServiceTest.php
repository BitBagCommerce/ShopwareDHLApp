<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\Shopware;

use BitBag\ShopwareDHLApp\API\Shopware\ShippingMethodApiService;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Criteria;
use Vin\ShopwareSdk\Data\Filter\ContainsFilter;
use Vin\ShopwareSdk\Data\Filter\EqualsFilter;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

class ShippingMethodApiServiceTest extends TestCase
{
    public const RULE = 'test';

    public const MIN = 1;

    public const MAX = 3;

    public const UNIT = 'day';

    public function testFindShippingMethodByShippingKey(): void
    {
        $shippingMethodRepository = $this->createMock(RepositoryInterface::class);
        $deliveryTimeRepository = $this->createMock(RepositoryInterface::class);
        $ruleRepository = $this->createMock(RepositoryInterface::class);

        $shippingMethodApiService = new ShippingMethodApiService(
            $shippingMethodRepository,
            $deliveryTimeRepository,
            $ruleRepository
        );
        $criteria = new Criteria();
        $criteria->addFilter(new ContainsFilter('name', Defaults::SHIPPING_METHOD_NAME));

        $context = $this->createMock(Context::class);

        $shippingMethodRepository->expects(self::once())->method('searchIds')->with($criteria, $context);

        $shippingMethodApiService->findShippingMethodByShippingKey($context);
    }

    public function testFindRuleByName(): void
    {
        $shippingMethodRepository = $this->createMock(RepositoryInterface::class);
        $deliveryTimeRepository = $this->createMock(RepositoryInterface::class);
        $ruleRepository = $this->createMock(RepositoryInterface::class);

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::RULE));

        $shippingMethodApiService = new ShippingMethodApiService(
            $shippingMethodRepository,
            $deliveryTimeRepository,
            $ruleRepository
        );

        $context = $this->createMock(Context::class);

        $ruleRepository->expects(self::once())->method('searchIds')->with($criteria, $context);

        $shippingMethodApiService->findRuleByName(self::RULE, $context);
    }

    public function testFindDeliveryTimeByMinMax(): void
    {
        $shippingMethodRepository = $this->createMock(RepositoryInterface::class);
        $deliveryTimeRepository = $this->createMock(RepositoryInterface::class);
        $ruleRepository = $this->createMock(RepositoryInterface::class);

        $shippingMethodApiService = new ShippingMethodApiService(
            $shippingMethodRepository,
            $deliveryTimeRepository,
            $ruleRepository
        );

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('min', self::MIN));
        $criteria->addFilter(new EqualsFilter('max', self::MAX));
        $criteria->addFilter(new ContainsFilter('unit', self::UNIT));

        $context = $this->createMock(Context::class);

        $deliveryTimeRepository->expects(self::once())->method('searchIds')->with($criteria, $context);

        $shippingMethodApiService->findDeliveryTimeByMinMax(self::MIN, self::MAX, $context);
    }
}
