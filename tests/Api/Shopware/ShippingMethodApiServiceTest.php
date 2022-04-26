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

    private Context $context;

    private ShippingMethodApiService $shippingMethodApiService;

    private RepositoryInterface $shippingMethodRepository;

    private RepositoryInterface $deliveryTimeRepository;

    private RepositoryInterface $ruleRepository;

    protected function setUp(): void
    {
        $this->shippingMethodRepository = $this->createMock(RepositoryInterface::class);
        $this->deliveryTimeRepository = $this->createMock(RepositoryInterface::class);
        $this->ruleRepository = $this->createMock(RepositoryInterface::class);

        $this->context = $this->createMock(Context::class);

        $this->shippingMethodApiService = new ShippingMethodApiService(
            $this->shippingMethodRepository,
            $this->deliveryTimeRepository,
            $this->ruleRepository
        );
    }

    public function testFindShippingMethodByShippingKey(): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new ContainsFilter('name', Defaults::SHIPPING_METHOD_NAME));

        $this->shippingMethodRepository->expects(self::once())->method('searchIds')->with($criteria, $this->context);

        $this->shippingMethodApiService->findShippingMethodByShippingKey($this->context);
    }

    public function testFindRuleByName(): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::RULE));

        $this->ruleRepository->expects(self::once())->method('searchIds')->with($criteria, $this->context);

        $this->shippingMethodApiService->findRuleByName(self::RULE, $this->context);
    }

    public function testFindDeliveryTimeByMinMax(): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('min', self::MIN));
        $criteria->addFilter(new EqualsFilter('max', self::MAX));
        $criteria->addFilter(new ContainsFilter('unit', self::UNIT));

        $this->deliveryTimeRepository->expects(self::once())->method('searchIds')->with($criteria, $this->context);

        $this->shippingMethodApiService->findDeliveryTimeByMinMax(self::MIN, self::MAX, $this->context);
    }
}
