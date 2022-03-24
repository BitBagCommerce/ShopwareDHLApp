<?php

namespace BitBag\ShopwareAppSkeleton\Factory;

interface CreateShippingMethodFactoryInterface
{
    public function create(string $ruleId, array $deliveryTime): array;
}