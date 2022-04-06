<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\Shopware;

use Vin\ShopwareSdk\Data\Context;

interface AvailabilityRuleCreatorInterface
{
    public function create(Context $context): void;
}
