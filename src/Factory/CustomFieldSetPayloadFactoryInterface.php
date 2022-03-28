<?php

namespace BitBag\ShopwareAppSkeleton\Factory;

interface CustomFieldSetPayloadFactoryInterface
{
    public function create(string $name, string $labelName, string $entityName): array;
}
