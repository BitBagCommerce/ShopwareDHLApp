<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

interface PackageDetailsFactoryInterface
{
    public function create(array $customFields, int $totalWeight): array;
}
