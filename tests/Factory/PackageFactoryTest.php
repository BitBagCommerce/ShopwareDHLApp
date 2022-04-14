<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use Alexcherniatin\DHL\Structures\PaymentData;
use BitBag\ShopwareDHLApp\Entity\Config;
use BitBag\ShopwareDHLApp\Factory\AddressFactory;
use BitBag\ShopwareDHLApp\Factory\PackageFactory;
use BitBag\ShopwareDHLApp\Factory\PaymentDataFactory;
use BitBag\ShopwareDHLApp\Factory\PieceFactory;
use BitBag\ShopwareDHLApp\Factory\ReceiverAddressFactory;
use BitBag\ShopwareDHLApp\Factory\ServiceDefinitionFactory;
use BitBag\ShopwareDHLApp\Factory\ShipmentFullDataFactory;
use BitBag\ShopwareDHLApp\Model\OrderData;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Entity\OrderAddress\OrderAddressEntity;

class PackageFactoryTest extends TestCase
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
        $config->setPaymentMethod(PaymentData::PAYMENT_METHOD_BANK_TRANSFER);
        $config->setAccountNumber('60000');
        $config->setEmail(self::EMAIL);

        $shippingAddress = new OrderAddressEntity();
        $shippingAddress->street = self::STREET . '' . self::HOUSE_NUMBER;
        $shippingAddress->zipcode = self::POST_CODE;
        $shippingAddress->city = self::CITY;
        $shippingAddress->firstName = 'Tester';
        $shippingAddress->lastName = 'Senderowich';
        $shippingAddress->phoneNumber = self::PHONE_NUMBER;

        $orderData = new OrderData(
            $shippingAddress,
            self::EMAIL,
            10.0,
            $this->getExampleData(),
            '321',
            '1234'
        );

        $addressFactory = new AddressFactory();
        $receiverAddressFactory = new ReceiverAddressFactory();
        $pieceFactory = new PieceFactory();
        $shipmentFactory = new ShipmentFullDataFactory();
        $paymentFactory = new PaymentDataFactory();
        $serviceDefinitionFactory = new ServiceDefinitionFactory();

        $packageFactory = new PackageFactory(
            $addressFactory,
            $receiverAddressFactory,
            $pieceFactory,
            $shipmentFactory,
            $paymentFactory,
            $serviceDefinitionFactory
        );
        self::assertSame(
            [
                'item' => [
                    'shipper' => [
                        'name' => self::NAME,
                        'postalCode' => self::POST_CODE,
                        'city' => self::CITY,
                        'street' => self::STREET,
                        'houseNumber' => self::HOUSE_NUMBER,
                        'contactPhone' => self::PHONE_NUMBER,
                        'contactEmail' => self::EMAIL,
                    ],
                    'receiver' => [
                        'addressType' => 'B',
                        'country' => 'PL',
                        'name' => self::NAME,
                        'postalCode' => self::POST_CODE,
                        'city' => self::CITY,
                        'street' => self::STREET,
                        'houseNumber' => self::HOUSE_NUMBER,
                        'contactPhone' => self::PHONE_NUMBER,
                        'contactEmail' => self::EMAIL,
                    ],
                    'pieceList' => [
                        [
                            'type' => 'PACKAGE',
                            'width' => 10,
                            'height' => 20,
                            'length' => 20,
                            'weight' => 10,
                            'quantity' => 1,
                            'nonStandard' => false,
                        ],
                    ],
                    'payment' => [
                        'paymentMethod' => PaymentData::PAYMENT_METHOD_BANK_TRANSFER,
                        'payerType' => PaymentData::PAYER_TYPE_SHIPPER,
                        'accountNumber' => '60000',
                    ],
                    'service' => [
                        'product' => 'AH',
                        'deliveryEvening' => false,
                    ],
                    'shipmentDate' => '2022-04-14',
                    'skipRestrictionCheck' => true,
                    'content' => 'Some package',
                ],
            ],
            $packageFactory->create($config, $orderData)
        );
    }

    private function getExampleData(): array
    {
        $customFields = [];

        $customFields[Defaults::PACKAGE_WIDTH] = 10;
        $customFields[Defaults::PACKAGE_HEIGHT] = 30;
        $customFields[Defaults::PACKAGE_DEPTH] = 20;
        $customFields[Defaults::PACKAGE_DESCRIPTION] = 'Some package';
        $customFields[Defaults::PACKAGE_SHIPPING_DATE] = '2022-04-14';
        $customFields[Defaults::PACKAGE_COUNTRY_CODE] = 'PL';

        return $customFields;
    }
}
