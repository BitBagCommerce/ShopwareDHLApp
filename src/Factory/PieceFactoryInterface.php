<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

interface PieceFactoryInterface
{
    public function create(array $customFields, int $totalWeight): array;
}
