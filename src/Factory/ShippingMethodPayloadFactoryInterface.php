<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

interface ShippingMethodPayloadFactoryInterface
{
    public function create(string $ruleId, IdSearchResult $deliveryTime): array;
}
