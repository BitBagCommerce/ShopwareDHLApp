<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\Shopware;

use BitBag\ShopwareDHLApp\API\Shopware\ShippingMethodApiService;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

class ShippingMethodApiServiceTest extends TestCase
{
    public const RULE = 'test';

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

        $context = $this->createMock(Context::class);
        $idSearchResult = $this->createMock(IdSearchResult::class);
        $shippingMethodRepository->method('searchIds')->willReturn($idSearchResult);

        self::assertEquals($idSearchResult, $shippingMethodApiService->findShippingMethodByShippingKey($context));
    }

    public function testFindRuleByName(): void
    {
        $shippingMethodRepository = $this->createMock(RepositoryInterface::class);
        $deliveryTimeRepository = $this->createMock(RepositoryInterface::class);
        $ruleRepository = $this->createMock(RepositoryInterface::class);

        $shippingMethodApiService = new ShippingMethodApiService(
            $shippingMethodRepository,
            $deliveryTimeRepository,
            $ruleRepository
        );

        $context = $this->createMock(Context::class);
        $idSearchResult = $this->createMock(IdSearchResult::class);
        $ruleRepository->method('searchIds')->willReturn($idSearchResult);

        self::assertEquals($idSearchResult, $shippingMethodApiService->findRuleByName(self::RULE, $context));
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

        $context = $this->createMock(Context::class);
        $idSearchResult = $this->createMock(IdSearchResult::class);
        $deliveryTimeRepository->method('searchIds')->willReturn($idSearchResult);

        self::assertEquals($idSearchResult, $shippingMethodApiService->findDeliveryTimeByMinMax(1, 3, $context));
    }
}
