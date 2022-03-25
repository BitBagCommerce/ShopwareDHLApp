<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

final class CustomFieldSetPayloadPayloadFactory implements CustomFieldSetPayloadFactoryInterface
{
    public function create(string $name, string $labelName, string $entityName): array
    {
        return [
            'name' => $name,
            'relations' => [
                [
                    'entityName' => $entityName,
                ],
            ],
            'config' => [
                'label' => ['en-GB' => $labelName],
                'translated' => true,
            ],
        ];
    }
}
