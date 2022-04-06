<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use BitBag\ShopwareDHLApp\Provider\Defaults;
use DateTime;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

final class ShippingMethodPayloadFactory implements ShippingMethodPayloadFactoryInterface
{
    public function create(string $ruleId, IdSearchResult $deliveryTime): array
    {
        $currentDateTime = new DateTime();

        if (0 < $deliveryTime->getTotal()) {
            $deliveryTimeForDHL = [
                'deliveryTimeId' => $deliveryTime->firstId(),
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
