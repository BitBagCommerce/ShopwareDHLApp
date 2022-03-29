<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

interface ShippingMethodPayloadFactoryInterface
{
    public function create(string $ruleId, array $deliveryTime): array;
}
