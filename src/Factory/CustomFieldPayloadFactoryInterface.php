<?php

namespace BitBag\ShopwareAppSkeleton\Factory;

interface CustomFieldPayloadFactoryInterface
{
    public function create(
        string $name,
        string $type,
        int $position,
        string $label,
        ?string $customFieldSetId = null,
        ?array $customFieldSet = null
    ): array;
}
