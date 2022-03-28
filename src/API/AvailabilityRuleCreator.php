<?php

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

class AvailabilityRuleCreator implements AvailabilityRuleCreatorInterface
{
    public function create(ClientInterface $client)
    {
        $rule = [
            'name' => Defaults::AVAILABILITY_RULE,
            'createdAt' => new \DateTime('now'),
            'priority' => 100,
        ];

        $client->createEntity('rule', $rule);
    }
}
