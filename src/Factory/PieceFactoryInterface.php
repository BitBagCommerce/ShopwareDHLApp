<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

interface PieceFactoryInterface
{
    public function create(array $customFields, float $totalWeight): array;
}
