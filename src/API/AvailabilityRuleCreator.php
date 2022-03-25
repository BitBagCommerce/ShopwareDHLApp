<?php

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

class AvailabilityRuleCreator implements AvailabilityRuleCreatorInterface
{
    public function create(ClientInterface $client)
    {
        $rule = [
            'name' => 'Always valid (Default)',
            'createdAt' => new \DateTime('now'),
            'priority' => 100,
        ];

        $client->createEntity('rule', $rule);
    }
}
