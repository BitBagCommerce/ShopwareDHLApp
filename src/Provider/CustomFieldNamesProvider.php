<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Provider;

final class CustomFieldNamesProvider implements CustomFieldNamesProviderInterface
{
    public function getFields(): array
    {
        return [
            [
                'name' => 'height',
                'label' => 'Height',
                'type' => 'int',
            ],
            [
                'name' => 'width',
                'label' => 'Width',
                'type' => 'int',
            ],
            [
                'name' => 'depth',
                'label' => 'Depth',
                'type' => 'int',
            ],
            [
                'name' => 'countryCode',
                'label' => 'Sender country code',
                'type' => 'text',
            ],
            [
                'name' => 'shippingDate',
                'label' => 'Shipping date (YYYY-MM-DD)',
                'type' => 'text',
            ],
            [
                'name' => 'insurance',
                'label' => 'Insurance value (you can leave empty)',
                'type' => 'int',
            ],
            [
                'name' => 'description',
                'label' => 'Package description',
                'type' => 'text',
            ],
        ];
    }
}
