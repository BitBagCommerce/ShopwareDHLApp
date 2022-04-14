<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use Alexcherniatin\DHL\Structures\PaymentData;
use Alexcherniatin\DHL\Structures\Piece;
use BitBag\ShopwareDHLApp\Factory\ShipmentFullDataFactory;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use PHPUnit\Framework\TestCase;

class ShipmentFullDataFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $addressStructure = $this->getAddressStructure();
        $receiverAddressStructure = $this->getReceiverAddressStructure();
        $pieceStructure = $this->getPieceStructure();
        $customFields = $this->getExampleData();
        $paymentStructure = $this->getPaymentDataStructure();
        $serviceDefinitionStructure = $this->getServiceDefinitionStructure();

        $shipmentFullDataFactory = new ShipmentFullDataFactory();

        $shipmentFullData = $shipmentFullDataFactory->create(
            $addressStructure,
            $receiverAddressStructure,
            $pieceStructure,
            $customFields,
            $paymentStructure,
            $serviceDefinitionStructure
        );

        self::assertSame(
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
                            'weight' => 20,
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
                        'insurance' => true,
                        'insuranceValue' => 100.0,
                    ],
                    'shipmentDate' => '2022-04-14',
                    'skipRestrictionCheck' => true,
                    'content' => 'Some package',
                ],
            ],
            $shipmentFullData
        );
    }

    private function getAddressStructure(): array
    {
        return
            [
                'name' => 'Tester Senderowich',
                'postalCode' => '01771',
                'city' => 'Warszawa',
                'street' => 'Braci Załuskich',
                'houseNumber' => '4a',
                'contactPhone' => '123456789',
                'contactEmail' => 'test@test.com',
            ];
    }

    private function getReceiverAddressStructure(): array
    {
        return
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
            ];
    }

    private function getPieceStructure(): array
    {
        $totalWeight = 20.0;
        $customFields = $this->getExampleData();

        return
            [
                'type' => Piece::TYPE_PACKAGE,
                'width' => $customFields[Defaults::PACKAGE_WIDTH],
                'height' => $customFields[Defaults::PACKAGE_HEIGHT],
                'length' => $customFields[Defaults::PACKAGE_DEPTH],
                'weight' => (int) $totalWeight,
                'quantity' => 1,
                'nonStandard' => false,
            ];
    }

    private function getPaymentDataStructure(): array
    {
        return
            [
                'paymentMethod' => PaymentData::PAYMENT_METHOD_BANK_TRANSFER,
                'payerType' => PaymentData::PAYER_TYPE_SHIPPER,
                'accountNumber' => '60000',
            ];
    }

    private function getServiceDefinitionStructure(): array
    {
        return
            [
                'product' => 'AH',
                'deliveryEvening' => false,
                'insurance' => true,
                'insuranceValue' => 100.0,
            ];
    }

    private function getExampleData(): array
    {
        $customFields = [];

        $customFields[Defaults::PACKAGE_WIDTH] = 10;
        $customFields[Defaults::PACKAGE_HEIGHT] = 30;
        $customFields[Defaults::PACKAGE_DEPTH] = 20;
        $customFields[Defaults::PACKAGE_DESCRIPTION] = 'Some package';
        $customFields[Defaults::PACKAGE_SHIPPING_DATE] = '2022-04-14';

        return $customFields;
    }
}
