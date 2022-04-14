<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use Alexcherniatin\DHL\Structures\PaymentData;
use BitBag\ShopwareDHLApp\Entity\Config;
use BitBag\ShopwareDHLApp\Factory\AddressFactory;
use PHPUnit\Framework\TestCase;

class AddressFactoryTest extends TestCase
{
    private const NAME = 'Tester Senderowich';

    private const POST_CODE = '01771';

    private const CITY = 'Warszawa';

    private const STREET = 'Braci Załuskich';

    private const HOUSE_NUMBER = '4a';

    private const PHONE_NUMBER = '123456789';

    private const EMAIL = 'test@test.com';

    public function testCreate(): void
    {
        $config = new Config();
        $config->setName(self::NAME);
        $config->setPostalCode(self::POST_CODE);
        $config->setCity(self::CITY);
        $config->setStreet(self::STREET);
        $config->setHouseNumber(self::HOUSE_NUMBER);
        $config->setPhoneNumber(self::PHONE_NUMBER);
        $config->setPayerType(PaymentData::PAYER_TYPE_SHIPPER);
        $config->setEmail(self::EMAIL);

        $addressFactory = new AddressFactory();

        self::assertEquals(
            [
                'name' => self::NAME,
                'postalCode' => self::POST_CODE,
                'city' => self::CITY,
                'street' => self::STREET,
                'houseNumber' => self::HOUSE_NUMBER,
                'contactPhone' => self::PHONE_NUMBER,
                'contactEmail' => self::EMAIL,
            ],
            $addressFactory->create($config)
        );
    }
}
