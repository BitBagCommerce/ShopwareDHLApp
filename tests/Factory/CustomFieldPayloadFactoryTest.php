<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use BitBag\ShopwareDHLApp\Factory\CustomFieldPayloadFactory;
use PHPUnit\Framework\TestCase;

class CustomFieldPayloadFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $customFieldPayloadFactory = new CustomFieldPayloadFactory();

        $customField = $this->getExampleCustomTextField();

        $this->assertSame(
            $this->getCustomFieldFromForText(),
            $customFieldPayloadFactory->create(
                $customField['customFieldName'],
                $customField['type'],
                $customField['key'],
                $customField['label'],
                '0a49f928ce8f40a4902003139f221fbe',
                null
            )
        );

        $customField = $this->getExampleCustomIntField();

        $this->assertSame(
            $this->getCustomFieldFromForInt(),
            $customFieldPayloadFactory->create(
                $customField['customFieldName'],
                $customField['type'],
                $customField['key'],
                $customField['label'],
                '0a49f928ce8f40a4902003139f221fbe',
                null
            )
        );
    }

    private function getCustomFieldFromForInt(): array
    {
        return [
            'name' => 'bitbag.shopware_dhl_app.package_details_height',
            'type' => 'int',
            'position' => 0,
            'config' => [
                'type' => 'number',
                'label' => [
                    'en-GB' => 'Height',
                ],
                'helpText' => [
                ],
                'placeholder' => [
                ],
                'componentName' => 'sw-field',
                'customFieldType' => 'number',
                'customFieldPosition' => 0,
                'numberType' => 'int',
            ],
            'customFieldSetId' => '0a49f928ce8f40a4902003139f221fbe',
        ];
    }

    private function getCustomFieldFromForText(): array
    {
        return [
            'name' => 'bitbag.shopware_dhl_app.package_details_countryCode',
            'type' => 'text',
            'position' => 0,
            'config' => [
                'type' => 'text',
                'label' => [
                    'en-GB' => 'Sender country code',
                ],
                'helpText' => [
                ],
                'placeholder' => [
                ],
                'componentName' => 'sw-field',
                'customFieldType' => 'text',
                'customFieldPosition' => 0,
            ],
            'customFieldSetId' => '0a49f928ce8f40a4902003139f221fbe',
        ];
    }

    public function getExampleCustomIntField(): array
    {
        return
            [
                'customFieldName' => 'bitbag.shopware_dhl_app.package_details_height',
                'type' => 'int',
                'key' => 0,
                'label' => 'Height',
            ];
    }

    public function getExampleCustomTextField(): array
    {
        return
            [
                'customFieldName' => 'bitbag.shopware_dhl_app.package_details_countryCode',
                'type' => 'text',
                'key' => 0,
                'label' => 'Sender country code',
            ];
    }
}
