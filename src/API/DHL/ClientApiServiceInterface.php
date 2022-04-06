<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\Struct\EntitySearchResult;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

interface ClientApiServiceInterface
{
    public function getOrder(Context $context, string $orderId): EntitySearchResult;

    public function findDeliveryTimeByMinMax(
        Context $context,
        int $min,
        int $max
    ): IdSearchResult;

    public function findShippingMethodByShippingKey(Context $context): IdSearchResult;

    public function findRuleByName(Context $context, string $name): IdSearchResult;

    public function findCustomFieldIdsByName(Context $context, string $name): IdSearchResult;

    public function findCustomFieldSetIdsByName(Context $context, string $name): IdSearchResult;
}
