<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use Alexcherniatin\DHL\DHL24;

interface ApiResolverInterface
{
    public function getApi(string $shopId): DHL24;
}
