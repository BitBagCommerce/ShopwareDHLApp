<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use BitBag\ShopwareDHLApp\Factory\CustomFieldPayloadFactory;
use PHPUnit\Framework\TestCase;

class CustomFieldPayloadFactoryTest extends TestCase
{
    public function testCreation(): void
    {
        $customFieldPayloadFactory = new CustomFieldPayloadFactory();

        foreach ($this->getExampleCustomFields() as $customField) {
            $this->assertSame($this->getCustomFieldFromParams(
                $customField['customFieldName'],
                $customField['type'],
                $customField['key'],
                $customField['label']
            ), $customFieldPayloadFactory->create(
                $customField['customFieldName'],
                $customField['type'],
                $customField['key'],
                $customField['label'],
                '0a49f928ce8f40a4902003139f221fbe',
                null
            ));
        }
    }

    private function getCustomFieldFromParams(
        string $customFieldName,
        string $type,
        int $position,
        string $label
    ): array {
        $customFieldType = 'text';

        if ('int' === $type) {
            $customFieldType = 'number';
        }

        $customField = [
            'name' => $customFieldName,
            'type' => $type,
            'position' => $position,
            'config' => [
                    'type' => $customFieldType,
                    'label' => [
                            'en-GB' => $label,
                        ],
                    'helpText' => [
                        ],
                    'placeholder' => [
                        ],
                    'componentName' => 'sw-field',
                    'customFieldType' => $customFieldType,
                    'customFieldPosition' => $position,
                ],
            'customFieldSetId' => '0a49f928ce8f40a4902003139f221fbe',
        ];

        if ('int' === $type) {
            $customField['config']['numberType'] = $type;
        }

        return $customField;
    }

    private function getExampleCustomFields(): array
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
