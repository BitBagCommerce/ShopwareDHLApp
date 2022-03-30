<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

interface ServiceDefinitionFactoryInterface
{
    public function create(array $customFields): array;
}
