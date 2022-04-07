<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

interface CustomFieldApiServiceInterface
{
    public function findCustomFieldIdsByName(Context $context, string $name): IdSearchResult;

    public function findCustomFieldSetIdsByName(Context $context, string $name): IdSearchResult;
}
