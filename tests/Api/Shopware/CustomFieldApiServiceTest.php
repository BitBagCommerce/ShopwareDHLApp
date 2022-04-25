<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\Shopware;

use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldApiService;
use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldApiServiceInterface;
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

    private CustomFieldApiServiceInterface $customFieldApiService;

    private Context $context;

    private RepositoryInterface $customFieldRepository;

    private RepositoryInterface $customFieldSetRepository;

    protected function setUp(): void
    {
        $this->customFieldRepository = $this->createMock(RepositoryInterface::class);
        $this->customFieldSetRepository = $this->createMock(RepositoryInterface::class);
        $this->context = $this->createMock(Context::class);

        $this->customFieldApiService = new CustomFieldApiService($this->customFieldRepository, $this->customFieldSetRepository);
    }

    public function testFindCustomFieldByName(): void
    {
        $criteria = new Criteria();

        $filter = new ContainsFilter('name', self::CUSTOM_FIELD_NAME);

        $criteria->addFilter(new MultiFilter('or', [$filter]));

        $this->customFieldRepository->expects(self::once())->method('search')->with($criteria, $this->context);

        $this->customFieldApiService->findCustomFieldsByName([$filter], $this->context);
    }

    public function testFindCustomFieldSetIdsByName(): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELD_NAME));

        $this->customFieldSetRepository->expects(self::once())->method('searchIds')->with($criteria, $this->context);

        $this->customFieldApiService->findCustomFieldSetIdsByName(self::CUSTOM_FIELD_NAME, $this->context);
    }
}
