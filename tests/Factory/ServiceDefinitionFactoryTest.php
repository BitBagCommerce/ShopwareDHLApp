<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use BitBag\ShopwareDHLApp\Factory\ServiceDefinitionFactory;
use PHPUnit\Framework\TestCase;

class ServiceDefinitionFactoryTest extends TestCase
{
    public function testCreateWithInsurance(): void
    {
        $customFields = [
            'bitbag.shopware_dhl_app.package_insurance' => 100,
        ];

        $serviceDefinitionFactory = new ServiceDefinitionFactory();

        $this->assertSame(
            [
                'product' => 'AH',
                'deliveryEvening' => false,
                'insurance' => true,
                'insuranceValue' => 100.0,
            ],
            $serviceDefinitionFactory->create($customFields)
        );
    }

    public function testCreateWithoutInsurance(): void
    {
        $customFields = [];

        $serviceDefinitionFactory = new ServiceDefinitionFactory();

        $this->assertSame(
            [
                'product' => 'AH',
                'deliveryEvening' => false,
            ],
            $serviceDefinitionFactory->create($customFields)
        );
    }
}
