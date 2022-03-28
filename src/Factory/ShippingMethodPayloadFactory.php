<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use BitBag\ShopwareAppSkeleton\Provider\Defaults;
use DateTime;

final class ShippingMethodPayloadFactory implements ShippingMethodPayloadFactoryInterface
{
    public function create(string $ruleId, array $deliveryTime): array
    {
        $currentDateTime = new DateTime();

        if (isset($deliveryTime['total']) && 0 < $deliveryTime['total']) {
            $deliveryTimeForDHL = [
                'deliveryTimeId' => $deliveryTime['data'][0],
            ];
        } else {
            $deliveryTimeForDHL = [
                'deliveryTime' => [
                    'name' => '1-3 days',
                    'min' => 1,
                    'max' => 3,
                    'unit' => 'day',
                    'createdAt' => $currentDateTime,
                ],
            ];
        }

        $DHLShippingMethod = [
            'name' => Defaults::SHIPPING_METHOD_NAME,
            'active' => true,
            'description' => Defaults::SHIPPING_METHOD_NAME,
            'taxType' => 'auto',
            'translated' => [
                'name' => Defaults::SHIPPING_METHOD_NAME,
            ],
            'availabilityRuleId' => $ruleId,
            'createdAt' => $currentDateTime,
        ];

        return array_merge($deliveryTimeForDHL, $DHLShippingMethod);
    }
}
