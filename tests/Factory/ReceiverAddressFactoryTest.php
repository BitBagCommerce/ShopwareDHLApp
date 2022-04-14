<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use BitBag\ShopwareDHLApp\Factory\ReceiverAddressFactory;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Entity\OrderAddress\OrderAddressEntity;

class ReceiverAddressFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $receiverAddressFactory = new ReceiverAddressFactory();

        $shippingAddress = new OrderAddressEntity();
        $shippingAddress->street = 'Braci Załuskich 4a';
        $shippingAddress->zipcode = '01771';
        $shippingAddress->city = 'Warszawa';
        $shippingAddress->firstName = 'Tester';
        $shippingAddress->lastName = 'Senderowicz';
        $shippingAddress->phoneNumber = '123456789';

        $customerEmail = 'test@test.com';

        $customFields = [
            Defaults::PACKAGE_COUNTRY_CODE => 'PL',
        ];

        self::assertSame(
            [
                'addressType' => 'B',
                'country' => 'PL',
                'name' => 'Tester Senderowicz',
                'postalCode' => '01771',
                'city' => 'Warszawa',
                'street' => 'Braci Załuskich',
                'houseNumber' => '4a',
                'contactPhone' => '123456789',
                'contactEmail' => 'test@test.com',
            ],
            $receiverAddressFactory->create($shippingAddress, $customerEmail, $customFields)
        );
    }
}
