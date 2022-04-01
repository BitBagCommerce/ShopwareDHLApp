<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\Shopware;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

final class AvailabilityRuleCreator implements AvailabilityRuleCreatorInterface
{
    public function create(ClientInterface $client): void
    {
        $rule = [
            'name' => Defaults::AVAILABILITY_RULE,
            'createdAt' => new \DateTime('now'),
            'priority' => 100,
        ];

        $client->createEntity('rule', $rule);
    }
}
