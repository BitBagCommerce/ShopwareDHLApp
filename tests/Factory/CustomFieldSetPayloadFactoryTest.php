<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use BitBag\ShopwareDHLApp\Factory\CustomFieldSetPayloadFactory;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use PHPUnit\Framework\TestCase;

class CustomFieldSetPayloadFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $customFieldSetFactory = new CustomFieldSetPayloadFactory();

        $customFieldSet = $customFieldSetFactory->create(
            Defaults::CUSTOM_FIELDS_PREFIX,
            'Package details',
            'order'
        );

        $expectedFieldSet = [
            'name' => Defaults::CUSTOM_FIELDS_PREFIX,
            'relations' => [
                [
                    'entityName' => 'order',
                ],
            ],
            'config' => [
                'label' => ['en-GB' => 'Package details'],
                'translated' => true,
            ],
        ];

        self::assertEquals($expectedFieldSet, $customFieldSet);
    }
}
