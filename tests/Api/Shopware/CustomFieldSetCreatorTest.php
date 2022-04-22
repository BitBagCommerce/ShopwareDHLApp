<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\Shopware;

use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldApiServiceInterface;
use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldSetCreator;
use BitBag\ShopwareDHLApp\Factory\CustomFieldSetPayloadFactory;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

class CustomFieldSetCreatorTest extends TestCase
{
    public function testCreate(): void
    {
        $customFieldSetRepository = $this->createMock(RepositoryInterface::class);

        $customFieldApiService = $this->createMock(CustomFieldApiServiceInterface::class);
        $idSearchResult = $this->createMock(IdSearchResult::class);
        $customFieldApiService->method('findCustomFieldSetIdsByName')->willReturn($idSearchResult);

        $customFieldSetPayloadFactory = new CustomFieldSetPayloadFactory();

        $customFieldSetCreator = new CustomFieldSetCreator($customFieldApiService, $customFieldSetPayloadFactory, $customFieldSetRepository);

        $context = $this->createMock(Context::class);

        $customFieldSet = $customFieldSetCreator->create($context);

        self::assertEquals(
            [
                'customFieldSetId' => null,
                'customFieldSet' => $idSearchResult,
            ],
            $customFieldSet
        );
    }
}
