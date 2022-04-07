<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\Shopware;

use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

interface ShippingMethodApiServiceInterface
{
    public function findShippingMethodByShippingKey(Context $context): IdSearchResult;

    public function findRuleByName(string $name, Context $context): IdSearchResult;

    public function findDeliveryTimeByMinMax(
        int $min,
        int $max,
        Context $context
    ): IdSearchResult;
}
