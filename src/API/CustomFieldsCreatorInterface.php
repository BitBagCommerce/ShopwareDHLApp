<?php

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface CustomFieldsCreatorInterface
{
    public function create(ClientInterface $client);
}
