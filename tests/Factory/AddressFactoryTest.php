<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use Alexcherniatin\DHL\Structures\PaymentData;
use BitBag\ShopwareDHLApp\Entity\Config;
use BitBag\ShopwareDHLApp\Factory\AddressFactory;
use PHPUnit\Framework\TestCase;

class AddressFactoryTest extends TestCase
{
    public function testCreation(): void
    {
        $config = new Config();
        $config->setName('Tester Senderowich');
        $config->setPostalCode('01771');
        $config->setCity('Warszawa');
        $config->setStreet('Braci Załuskich');
        $config->setHouseNumber('4a');
        $config->setPhoneNumber('123456789');
        $config->setPayerType(PaymentData::PAYER_TYPE_SHIPPER);
        $config->setEmail('test@test.com');

        $addressFactory = new AddressFactory();

        $this->assertSame(
            [
                'name' => 'Tester Senderowich',
                'postalCode' => '01771',
                'city' => 'Warszawa',
                'street' => 'Braci Załuskich',
                'houseNumber' => '4a',
                'contactPhone' => '123456789',
                'contactEmail' => 'test@test.com',
            ],
            $addressFactory->create($config)
        );
    }
}
