<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

interface ShippingMethodApiServiceInterface
{
    public function findShippingMethodByShippingKey(Context $context): IdSearchResult;

    public function findRuleByName(Context $context, string $name): IdSearchResult;

    public function findDeliveryTimeByMinMax(
        Context $context,
        int $min,
        int $max
    ): IdSearchResult;
}
