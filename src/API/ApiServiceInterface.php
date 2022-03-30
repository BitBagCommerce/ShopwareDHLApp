<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use Alexcherniatin\DHL\DHL24;

interface ApiServiceInterface
{
    public function getApi(string $shopId): DHL24;
}
