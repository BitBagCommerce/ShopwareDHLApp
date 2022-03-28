<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface CustomFieldSetCreatorInterface
{
    public function create(ClientInterface $client): array;
}
