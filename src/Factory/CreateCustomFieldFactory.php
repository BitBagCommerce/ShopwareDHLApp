<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

final class CreateCustomFieldFactory implements CreateCustomFieldFactoryInterface
{
    public function create(
        string $name,
        string $type,
        int $position,
        string $label,
        ?string $customFieldSetId = null,
        ?array $customFieldSet = null
    ): array {
        $customFieldArr = [
            'name' => $name,
            'type' => $type,
            'position' => $position,
            'config' => [
                'type' => $type,
                'label' => ['en-GB' => $label],
                'helpText' => [],
                'placeholder' => [],
                'componentName' => 'sw-field',
                'customFieldType' => $type,
                'customFieldPosition' => $position,
            ],
        ];

        if ($customFieldSetId) {
            $customFieldArr['customFieldSetId'] = $customFieldSetId;
        } elseif ($customFieldSet) {
            $customFieldArr['customFieldSet'] = $customFieldSet;
        }

        if ('int' === $type) {
            $customFieldArr['config']['type'] = 'number';
            $customFieldArr['config']['numberType'] = $type;
            $customFieldArr['config']['customFieldType'] = 'number';
        }

        return $customFieldArr;
    }
}
