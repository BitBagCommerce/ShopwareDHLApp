<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use Alexcherniatin\DHL\Structures\PaymentData;
use BitBag\ShopwareDHLApp\Entity\Config;
use BitBag\ShopwareDHLApp\Factory\PaymentDataFactory;
use PHPUnit\Framework\TestCase;

class PaymentDataFactoryTest extends TestCase
{
    public const ACCOUNT_NUMBER = '60000';

    public function testCreate(): void
    {
        $paymentDataFactory = new PaymentDataFactory();
        $config = new Config();
        $config->setPaymentMethod(PaymentData::PAYMENT_METHOD_BANK_TRANSFER);
        $config->setPayerType(PaymentData::PAYER_TYPE_SHIPPER);
        $config->setAccountNumber(self::ACCOUNT_NUMBER);

        self::assertSame(
            [
                'paymentMethod' => PaymentData::PAYMENT_METHOD_BANK_TRANSFER,
                'payerType' => PaymentData::PAYER_TYPE_SHIPPER,
                'accountNumber' => self::ACCOUNT_NUMBER,
            ],
            $paymentDataFactory->create($config)
        );
    }
}
