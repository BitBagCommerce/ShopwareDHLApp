<?php

namespace BitBag\ShopwareAppSkeleton\Factory;

final class CreateCustomFieldSetFactory implements CreateCustomFieldSetFactoryInterface
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