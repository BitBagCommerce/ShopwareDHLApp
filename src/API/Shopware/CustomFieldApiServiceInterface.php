<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\Shopware;

use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\Struct\EntitySearchResult;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

interface CustomFieldApiServiceInterface
{
    public function findCustomFieldsByName(array $filters, Context $context): EntitySearchResult;

    public function findCustomFieldSetIdsByName(string $name, Context $context): IdSearchResult;
}
