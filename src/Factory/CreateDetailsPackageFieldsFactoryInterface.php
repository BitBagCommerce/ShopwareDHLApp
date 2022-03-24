<?php

namespace BitBag\ShopwareAppSkeleton\Factory;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface CreateDetailsPackageFieldsFactoryInterface
{
    public function create(ClientInterface $client): array;
}