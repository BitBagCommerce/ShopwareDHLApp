<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use BitBag\ShopwareDHLApp\Model\LabelDataInterface;

interface LabelApiServiceInterface
{
    public function fetchLabel(string $parcelId, string $shopId): LabelDataInterface;
}
