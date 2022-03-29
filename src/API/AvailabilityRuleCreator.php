<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

class AvailabilityRuleCreator implements AvailabilityRuleCreatorInterface
{
    /**
     * @return void
     */
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
