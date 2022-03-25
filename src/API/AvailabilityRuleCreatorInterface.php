<?php

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface AvailabilityRuleCreatorInterface
{
    public function create(ClientInterface $client);
}
