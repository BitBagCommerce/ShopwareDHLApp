<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use BitBag\ShopwareAppSkeleton\Model\LabelDataInterface;

interface LabelFetcherInterface
{
    public function fetchLabel(string $shopId, string $parcelId): LabelDataInterface;
}
