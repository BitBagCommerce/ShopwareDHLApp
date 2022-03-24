<?php

namespace BitBag\ShopwareAppSkeleton\Factory;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface CreateCustomFieldFactoryInterface
{
    public function create(
        string $name,
        string $type, int $position,
        string $label,
        ?string $customFieldSetId = null,
        ?array $customFieldSet = null
    ): array;
}
