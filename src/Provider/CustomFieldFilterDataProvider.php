<?php

namespace BitBag\ShopwareDHLApp\Provider;

use Vin\ShopwareSdk\Data\Filter\ContainsFilter;

final class CustomFieldFilterDataProvider implements CustomFieldFilterDataProviderInterface
{
    public function getCustomFieldsFilter(array $customFieldNames): array
    {
        $filters = [];

        foreach ($customFieldNames as $name) {
            $filter = new ContainsFilter('name', Defaults::CUSTOM_FIELDS_PREFIX.'_'.$name['name']);
            $filters[] = $filter;
        }

        return $filters;
    }
}
