<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\Shopware;

use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldFilterInterface;
use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldsCreator;
use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldSetCreatorInterface;
use BitBag\ShopwareDHLApp\Factory\CustomFieldPayloadFactoryInterface;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

class CustomFieldsCreatorTest extends TestCase
{
    public function testCreate(): void
    {
        $customFieldRepository = $this->createMock(RepositoryInterface::class);

        $customFieldFilter = $this->createMock(CustomFieldFilterInterface::class);

        $customFieldPayloadFactory = $this->createMock(CustomFieldPayloadFactoryInterface::class);

        $customFieldSetCreator = $this->createMock(CustomFieldSetCreatorInterface::class);

        $customFieldsCreator = new CustomFieldsCreator(
            $customFieldFilter,
            $customFieldPayloadFactory,
            $customFieldSetCreator,
            $customFieldRepository
        );

        $context = $this->createMock(Context::class);

        $customFieldsCreator->create($context);

        self::assertTrue(true);
    }
}
