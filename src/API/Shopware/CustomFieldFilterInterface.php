<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\Shopware;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface CustomFieldFilterInterface
{
    public function filter(ClientInterface $client): array;
}
