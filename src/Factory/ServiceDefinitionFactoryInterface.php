<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

interface ServiceDefinitionFactoryInterface
{
    public function create(array $customFields): array;
}
