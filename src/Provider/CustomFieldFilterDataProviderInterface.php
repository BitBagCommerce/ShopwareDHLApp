<?php

namespace BitBag\ShopwareDHLApp\Provider;

interface CustomFieldFilterDataProviderInterface
{
    public function getCustomFieldsFilter(array $customFieldNames): array;
}
