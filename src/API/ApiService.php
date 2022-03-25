<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use Alexcherniatin\DHL\DHL24;
use Alexcherniatin\DHL\Exceptions\SoapException;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Exception\ConfigNotFoundException;
use BitBag\ShopwareAppSkeleton\Repository\ConfigRepositoryInterface;

final class ApiService
{
    private ConfigRepositoryInterface $configRepository;

    public function __construct(ConfigRepositoryInterface $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @throws SoapException
     */
    public function getApi(string $shopId): ?DHL24
    {
        /** @var ConfigInterface|null $config */
        $config = $this->configRepository->findOneBy(['shop' => $shopId]);

        if (null === $config) {
            throw new ConfigNotFoundException('Config not found');
        }

        return new DHL24($config->getUsername(), $config->getPassword(), $config->getAccountNumber(), true);
    }
}
