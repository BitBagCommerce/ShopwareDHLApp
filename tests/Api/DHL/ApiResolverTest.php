<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Tests\Api\DHL;

use Alexcherniatin\DHL\DHL24;
use BitBag\ShopwareDHLApp\API\DHL\ApiResolver;
use BitBag\ShopwareDHLApp\Entity\Config;
use BitBag\ShopwareDHLApp\Repository\ConfigRepository;
use PHPUnit\Framework\TestCase;

class ApiResolverTest extends TestCase
{
    public const SHOP_ID = 'test123';

    public const USERNAME = 'test';

    public const ACCOUNT_NUMBER = '60000';

    public const PASSWORD = 'secret';

    public function testGetApi(): void
    {
        $configRepository = $this->createMock(ConfigRepository::class);
        $config = new Config();
        $config->setUsername(self::USERNAME);
        $config->setAccountNumber(self::ACCOUNT_NUMBER);
        $config->setPassword(self::PASSWORD);

        $configRepository->method('findOneBy')->willReturn($config);

        $apiResolver = new ApiResolver($configRepository);

        self::assertInstanceOf(DHL24::class, $apiResolver->getApi(self::SHOP_ID));
    }
}
