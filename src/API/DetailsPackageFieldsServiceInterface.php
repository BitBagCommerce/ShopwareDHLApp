<?php

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface DetailsPackageFieldsServiceInterface
{
    public function create(ClientInterface $client): array;
}
