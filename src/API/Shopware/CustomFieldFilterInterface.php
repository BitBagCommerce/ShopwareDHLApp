<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\Shopware;

use Vin\ShopwareSdk\Data\Context;

interface CustomFieldFilterInterface
{
    public function filter(Context $context): array;
}
