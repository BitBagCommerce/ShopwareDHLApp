<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use Alexcherniatin\DHL\Exceptions\InvalidStructureException;
use Alexcherniatin\DHL\Structures\Piece;
use BitBag\ShopwareDHLApp\Provider\Defaults;

final class PieceFactory implements PieceFactoryInterface
{
    /**
     * @throws InvalidStructureException
     */
    public function create(array $customFields, float $totalWeight): array
    {
        return (new Piece())
            ->setType(Piece::TYPE_PACKAGE)
            ->setWidth($customFields[Defaults::PACKAGE_WIDTH])
            ->setHeight($customFields[Defaults::PACKAGE_HEIGHT])
            ->setLength($customFields[Defaults::PACKAGE_DEPTH])
            ->setWeight((int) $totalWeight)
            ->setQuantity(1)
            ->setNonStandard(false)
            ->structure();
    }
}
