<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

final class CustomFieldSetPayloadFactory implements CustomFieldSetPayloadFactoryInterface
{
    /**
     * @return ((string[]|true)[]|string)[]
     *
     * @psalm-return array{name: string, relations: array{0: array{entityName: string}}, config: array{label: array{'en-GB': string}, translated: true}}
     */
    public function create(
        string $name,
        string $labelName,
        string $entityName
    ): array {
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
