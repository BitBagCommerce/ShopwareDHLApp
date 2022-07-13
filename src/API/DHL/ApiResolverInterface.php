<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use Alexcherniatin\DHL\DHL24;

interface ApiResolverInterface
{
    public function getApi(string $shopId, string $salesChannelId): DHL24;
}
