<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Provider;

interface CustomFieldNamesProviderInterface
{
    public function getFields(): array;
}
