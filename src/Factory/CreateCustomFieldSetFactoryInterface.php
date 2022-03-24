<?php

namespace BitBag\ShopwareAppSkeleton\Factory;

interface CreateCustomFieldSetFactoryInterface
{
    public function create(string $name, string $labelName, string $entityName): array;
}