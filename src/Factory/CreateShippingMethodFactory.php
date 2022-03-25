<?php

namespace BitBag\ShopwareAppSkeleton\Factory;

use DateTime;

final class CreateShippingMethodFactory implements CreateShippingMethodFactoryInterface
{
    public function create(string $ruleId, array $deliveryTime): array
    {
        $shippingKey = '';
        $currentDateTime = new DateTime('now');

        $DHLShippingMethod = [
            'name' => $shippingKey,
            'active' => true,
            'description' => $shippingKey,
            'taxType' => 'auto',
            'translated' => [
                'name' => $shippingKey,
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
