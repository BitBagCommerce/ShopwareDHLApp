<?php

namespace BitBag\ShopwareAppSkeleton\Factory;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface DetailsPackageFieldsServiceInterface
{
    public function create(ClientInterface $client): array;
}
