<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\Shopware;

use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldApiServiceInterface;
use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldSetCreator;
use BitBag\ShopwareDHLApp\Factory\CustomFieldSetPayloadFactoryInterface;
use PHPUnit\Framework\TestCase;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

class CustomFieldSetCreatorTest extends TestCase
{
    public const TOTAL = 1;

    public const CUSTOM_FIELD_SET_ID = '123';

    private Context $context;

    private CustomFieldApiServiceInterface $customFieldApiService;

    private IdSearchResult $idSearchResult;

    private CustomFieldSetPayloadFactoryInterface $customFieldSetPayloadFactory;

    private CustomFieldSetCreator $customFieldSetCreator;

    protected function setUp(): void
    {
        $customFieldSetRepository = $this->createMock(RepositoryInterface::class);
        $this->context = $this->createMock(Context::class);
        $this->customFieldApiService = $this->createMock(CustomFieldApiServiceInterface::class);
        $this->idSearchResult = $this->createMock(IdSearchResult::class);
        $this->customFieldSetPayloadFactory = $this->createMock(CustomFieldSetPayloadFactoryInterface::class);
        $this->customFieldSetCreator = new CustomFieldSetCreator($this->customFieldApiService, $this->customFieldSetPayloadFactory, $customFieldSetRepository);
    }

    public function testCreate(): void
    {
        $this->customFieldApiService->method('findCustomFieldSetIdsByName')->willReturn($this->idSearchResult);

        $customFieldSet = $this->customFieldSetCreator->create($this->context);

        self::assertEquals(
            [
                'customFieldSetId' => null,
                'customFieldSet' => $this->idSearchResult,
            ],
            $customFieldSet
        );
    }

    public function testCreateWithCustomFieldSetId()
    {
        $this->idSearchResult->method('getTotal')->willReturn(self::TOTAL);
        $this->idSearchResult->method('firstId')->willReturn(self::CUSTOM_FIELD_SET_ID);

        $this->customFieldApiService->method('findCustomFieldSetIdsByName')->willReturn($this->idSearchResult);

        $customFieldSet = $this->customFieldSetCreator->create($this->context);

        self::assertEquals(
            [
                'customFieldSetId' => self::CUSTOM_FIELD_SET_ID,
                'customFieldSet' => $this->idSearchResult,
            ],
            $customFieldSet
        );
    }
}
