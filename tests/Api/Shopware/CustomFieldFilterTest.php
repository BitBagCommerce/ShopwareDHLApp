<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\Shopware;

use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldApiService;
use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldFilter;
use BitBag\ShopwareDHLApp\Provider\CustomFieldFilterDataProvider;
use BitBag\ShopwareDHLApp\Provider\CustomFieldNamesProvider;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Criteria;
use Vin\ShopwareSdk\Data\Filter\EqualsFilter;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

class CustomFieldFilterTest extends TestCase
{
    public function testFilter(): void
    {
        $customFieldRepository = $this->createMock(RepositoryInterface::class);
        $customFieldSetRepository = $this->createMock(RepositoryInterface::class);
        $customFieldFilterDataProvider = new CustomFieldFilterDataProvider();

        $context = $this->createMock(Context::class);

        $customFieldNamesProvider = new CustomFieldNamesProvider();
        $customFieldApiServiceInterface = new CustomFieldApiService($customFieldRepository, $customFieldSetRepository);

        $customFieldFilter = new CustomFieldFilter($customFieldNamesProvider, $customFieldApiServiceInterface, $customFieldFilterDataProvider);

        $this->assertEquals(
            $this->getExampleData(),
            $customFieldFilter->filter($context)
        );
    }

    public function getExampleData()
    {
        return [
            [
                'customFieldName' => 'bitbag.shopware_dhl_app.package_details_height',
                'type' => 'int',
                'key' => 0,
                'label' => 'Height',
            ],
            [
                'customFieldName' => 'bitbag.shopware_dhl_app.package_details_width',
                'type' => 'int',
                'key' => 1,
                'label' => 'Width',
            ],
            [
                'customFieldName' => 'bitbag.shopware_dhl_app.package_details_depth',
                'type' => 'int',
                'key' => 2,
                'label' => 'Depth',
            ],
            [
                'customFieldName' => 'bitbag.shopware_dhl_app.package_details_countryCode',
                'type' => 'text',
                'key' => 3,
                'label' => 'Sender country code',
            ],
            [
                'customFieldName' => 'bitbag.shopware_dhl_app.package_details_shippingDate',
                'type' => 'text',
                'key' => 4,
                'label' => 'Shipping date (YYYY-MM-DD)',
            ],
            [
                'customFieldName' => 'bitbag.shopware_dhl_app.package_details_insurance',
                'type' => 'int',
                'key' => 5,
                'label' => 'Insurance value (you can leave empty)',
            ],
            [
                'customFieldName' => 'bitbag.shopware_dhl_app.package_details_description',
                'type' => 'text',
                'key' => 6,
                'label' => 'Package description',
            ],
        ];
    }
}
