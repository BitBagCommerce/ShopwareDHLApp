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
    public function testCreate(): void
    {
        $config = new Config();
        $config->setName('Tester Senderowich');
        $config->setPostalCode('01771');
        $config->setCity('Warszawa');
        $config->setStreet('Braci Załuskich');
        $config->setHouseNumber('4a');
        $config->setPhoneNumber('123456789');
        $config->setPayerType(PaymentData::PAYER_TYPE_SHIPPER);
        $config->setPaymentMethod(PaymentData::PAYMENT_METHOD_BANK_TRANSFER);
        $config->setAccountNumber('60000');
        $config->setEmail('test@test.com');

        $shippingAddress = new OrderAddressEntity();
        $shippingAddress->street = 'Braci Załuskich 4a';
        $shippingAddress->zipcode = '01771';
        $shippingAddress->city = 'Warszawa';
        $shippingAddress->firstName = 'Tester';
        $shippingAddress->lastName = 'Senderowicz';
        $shippingAddress->phoneNumber = '123456789';

        $orderData = new OrderData(
            $shippingAddress,
            'test@test.com',
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
        $this->assertSame(
            [
                'item' => [
                    'shipper' => [
                        'name' => 'Tester Senderowich',
                        'postalCode' => '01771',
                        'city' => 'Warszawa',
                        'street' => 'Braci Załuskich',
                        'houseNumber' => '4a',
                        'contactPhone' => '123456789',
                        'contactEmail' => 'test@test.com',
                    ],
                    'receiver' => [
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
                        'paymentMethod' => 'BANK_TRANSFER',
                        'payerType' => 'SHIPPER',
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
