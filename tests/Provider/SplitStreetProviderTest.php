<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Provider;

use BitBag\ShopwareDHLApp\Exception\StreetCannotBeSplitException;
use BitBag\ShopwareDHLApp\Provider\SplitStreetProvider;
use BitBag\ShopwareDHLApp\Provider\SplitStreetProviderInterface;
use PHPUnit\Framework\TestCase;

class SplitStreetProviderTest extends TestCase
{
    private SplitStreetProviderInterface $splitStreetProvider;

    public const SINGLE_STREET = 'Testowa 12';

    public const TRIPLE_STREET = 'Os. Smoka Wawelskiego 12B';

    public const NUMERIC_STREET = '1 Maja 12A';

    public const NO_HOUSE_NUMBER = 'Testowa';

    protected function setUp(): void
    {
        $this->splitStreetProvider = new SplitStreetProvider();
    }

    public function testSingleStreetName(): void
    {
        $street = $this->splitStreetProvider->splitStreet(self::SINGLE_STREET);

        self::assertEquals([self::SINGLE_STREET, 'Testowa', 12], $street);
    }

    public function testTripleStreetName(): void
    {
        $street = $this->splitStreetProvider->splitStreet(self::TRIPLE_STREET);

        self::assertEquals([self::TRIPLE_STREET, 'Os. Smoka Wawelskiego', '12B'], $street);
    }

    public function testNumericStreetName(): void
    {
        $street = $this->splitStreetProvider->splitStreet(self::NUMERIC_STREET);

        self::assertEquals([self::NUMERIC_STREET, '1 Maja', '12A'], $street);
    }

    public function testStreetWithoutHouseNumber(): void
    {
        $this->expectException(StreetCannotBeSplitException::class);

        $this->splitStreetProvider->splitStreet(self::NO_HOUSE_NUMBER);
    }
}
