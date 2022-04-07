<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\Shopware;

use BitBag\ShopwareDHLApp\Provider\Defaults;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

final class AvailabilityRuleCreator implements AvailabilityRuleCreatorInterface
{
    private RepositoryInterface $ruleRepository;

    public function __construct(RepositoryInterface $ruleRepository)
    {
        $this->ruleRepository = $ruleRepository;
    }

    public function create(Context $context): void
    {
        $this->ruleRepository->create([
            'name' => Defaults::AVAILABILITY_RULE,
            'createdAt' => new \DateTime('now'),
            'priority' => 100,
        ], $context);
    }
}
