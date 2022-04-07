<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

interface CustomFieldSetPayloadFactoryInterface
{
    public function create(
        string $name,
        string $labelName,
        string $entityName
    ): array;
}
