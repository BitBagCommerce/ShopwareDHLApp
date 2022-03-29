<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Provider;

final class CustomFieldNamesProvider implements CustomFieldNamesProviderInterface
{
    /**
     * @return string[][]
     *
     * @psalm-return array{0: array{name: 'height', label: 'Height', type: 'int'}, 1: array{name: 'width', label: 'Width', type: 'int'}, 2: array{name: 'depth', label: 'Depth', type: 'int'}, 3: array{name: 'countryCode', label: 'Sender country code', type: 'text'}, 4: array{name: 'shippingDate', label: 'Shipping date', type: 'text'}}
     */
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
                'label' => 'Shipping date',
                'type' => 'text',
            ],
        ];
    }
}
