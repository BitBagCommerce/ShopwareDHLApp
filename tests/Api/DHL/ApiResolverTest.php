<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\DHL;

use BitBag\ShopwareDHLApp\API\DHL\ApiResolver;
use BitBag\ShopwareDHLApp\Exception\ConfigNotFoundException;
use BitBag\ShopwareDHLApp\Repository\ConfigRepository;
use PHPUnit\Framework\TestCase;

class ApiResolverTest extends TestCase
{
    private const SHOP_ID = 'test123';

    public function testGetApi(): void
    {
        $configRepository = $this->createMock(ConfigRepository::class);
        $configRepository
            ->method('findOneBy')
            ->willReturn(null);

        $apiResolver = new ApiResolver($configRepository);

        $this->expectException(ConfigNotFoundException::class);
        $apiResolver->getApi(self::SHOP_ID, '');
    }
}
