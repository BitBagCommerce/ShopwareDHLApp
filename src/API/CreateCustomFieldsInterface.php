<?php

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface CreateCustomFieldsInterface
{
    public function create(ClientInterface $client);
}
