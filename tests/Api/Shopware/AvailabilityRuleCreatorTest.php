<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\Shopware;

use BitBag\ShopwareDHLApp\API\Shopware\AvailabilityRuleCreator;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

class AvailabilityRuleCreatorTest extends TestCase
{
    public function testCreate(): void
    {
        $repository = $this->createMock(RepositoryInterface::class);
        $context = $this->createMock(Context::class);

        $repository->expects(self::once())->method('create')->with([
            'name' => Defaults::AVAILABILITY_RULE,
            'priority' => 100,
        ], $context);

        $availabilityRuleCreator = new AvailabilityRuleCreator($repository);
        $availabilityRuleCreator->create($context);
    }
}
