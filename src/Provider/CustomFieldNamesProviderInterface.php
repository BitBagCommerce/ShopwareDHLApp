<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Provider;

interface CustomFieldNamesProviderInterface
{
    public function getFields(): array;
}
