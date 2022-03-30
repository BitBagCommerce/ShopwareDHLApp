<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use Alexcherniatin\DHL\Exceptions\InvalidStructureException;
use Alexcherniatin\DHL\Structures\Piece;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

final class PackageDetailsFactory implements PackageDetailsFactoryInterface
{
    /**
     * @throws InvalidStructureException
     */
    public function create(array $customFields, int $totalWeight): array
    {
        return (new Piece())
            ->setType(Piece::TYPE_PACKAGE)
            ->setWidth($customFields[Defaults::PACKAGE_WIDTH])
            ->setHeight($customFields[Defaults::PACKAGE_HEIGHT])
            ->setLength($customFields[Defaults::PACKAGE_DEPTH])
            ->setWeight($totalWeight)
            ->setQuantity(1)
            ->setNonStandard(false)
            ->structure();
    }
}
