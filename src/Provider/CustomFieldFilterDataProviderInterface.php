<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Provider;

interface CustomFieldFilterDataProviderInterface
{
    public function getCustomFieldFilter(array $customFieldNames): array;
}
