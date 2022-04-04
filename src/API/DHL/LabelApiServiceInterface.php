<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use BitBag\ShopwareAppSkeleton\Model\LabelDataInterface;

interface LabelApiServiceInterface
{
    public function fetchLabel(string $parcelId, string $shopId): LabelDataInterface;
}
