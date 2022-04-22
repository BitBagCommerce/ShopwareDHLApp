<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\Shopware;

use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldApiService;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Criteria;
use Vin\ShopwareSdk\Data\Filter\ContainsFilter;
use Vin\ShopwareSdk\Data\Filter\EqualsFilter;
use Vin\ShopwareSdk\Data\Filter\MultiFilter;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

class CustomFieldApiServiceTest extends TestCase
{
    public const CUSTOM_FIELD_NAME = Defaults::PACKAGE_DESCRIPTION;

    public function testFindCustomFieldByName(): void
    {
        $customFieldRepository = $this->createMock(RepositoryInterface::class);
        $customFieldSetRepository = $this->createMock(RepositoryInterface::class);
        $context = $this->createMock(Context::class);

        $criteria = new Criteria();
        $filter = new ContainsFilter('name', self::CUSTOM_FIELD_NAME);
        $criteria->addFilter(new MultiFilter('or', [$filter]));

        $customFieldRepository->expects(self::once())->method('search')->with($criteria, $context);

        $customFieldApiService = new CustomFieldApiService($customFieldRepository, $customFieldSetRepository);

        $customFieldApiService->findCustomFieldsByName([$filter], $context);
    }

    public function testFindCustomFieldSetIdsByName(): void
    {
        $customFieldRepository = $this->createMock(RepositoryInterface::class);
        $customFieldSetRepository = $this->createMock(RepositoryInterface::class);
        $context = $this->createMock(Context::class);

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELD_NAME));

        $customFieldSetRepository->expects(self::once())->method('searchIds')->with($criteria, $context);

        $customFieldApiService = new CustomFieldApiService($customFieldRepository, $customFieldSetRepository);

        $customFieldApiService->findCustomFieldSetIdsByName(self::CUSTOM_FIELD_NAME, $context);
    }
}
