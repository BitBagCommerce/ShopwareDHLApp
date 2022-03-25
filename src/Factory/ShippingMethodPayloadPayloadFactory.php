<?php

namespace BitBag\ShopwareAppSkeleton\Factory;

use BitBag\ShopwareAppSkeleton\Provider\Defaults;
use DateTime;

final class ShippingMethodPayloadPayloadFactory implements ShippingMethodPayloadFactoryInterface
{
    public function create(string $ruleId, array $deliveryTime): array
    {
        $currentDateTime = new DateTime();

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

        if (isset($deliveryTime['total']) && $deliveryTime['total'] > 0) {
            $DHLShippingMethod = array_merge($DHLShippingMethod, [
                'deliveryTimeId' => $deliveryTime['data'][0],
            ]);
        } else {
            $DHLShippingMethod = array_merge($DHLShippingMethod, [
                'deliveryTime' => [
                    'name' => '1-3 days',
                    'min' => 1,
                    'max' => 3,
                    'unit' => 'day',
                    'createdAt' => $currentDateTime,
                ],
            ]);
        }

        return $DHLShippingMethod;
    }
}
