<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

interface CustomFieldPayloadFactoryInterface
{
    public function create(
        string $name,
        string $type,
        int $position,
        string $label,
        ?string $customFieldSetId = null,
        ?IdSearchResult $customFieldSet = null
    ): array;
}
