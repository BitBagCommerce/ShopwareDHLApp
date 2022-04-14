<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Factory;

use Alexcherniatin\DHL\Structures\Piece;
use BitBag\ShopwareDHLApp\Factory\PieceFactory;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use PHPUnit\Framework\TestCase;

class PieceFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $pieceFactory = new PieceFactory();

        $totalWeight = 20.0;
        $customFields = $this->getExampleData();

        self::assertSame(
            [
                'type' => Piece::TYPE_PACKAGE,
                'width' => $customFields[Defaults::PACKAGE_WIDTH],
                'height' => $customFields[Defaults::PACKAGE_HEIGHT],
                'length' => $customFields[Defaults::PACKAGE_DEPTH],
                'weight' => (int) $totalWeight,
                'quantity' => 1,
                'nonStandard' => false,
            ],
            $pieceFactory->create($customFields, $totalWeight)
        );
    }

    private function getExampleData(): array
    {
        $customFields = [];

        $customFields[Defaults::PACKAGE_WIDTH] = 10;
        $customFields[Defaults::PACKAGE_HEIGHT] = 30;
        $customFields[Defaults::PACKAGE_DEPTH] = 20;

        return $customFields;
    }
}
