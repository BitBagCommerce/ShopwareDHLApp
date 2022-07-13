<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use Alexcherniatin\DHL\DHL24;
use Alexcherniatin\DHL\Exceptions\SoapException;
use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use BitBag\ShopwareDHLApp\Exception\ConfigNotFoundException;
use BitBag\ShopwareDHLApp\Repository\ConfigRepositoryInterface;

final class ApiResolver implements ApiResolverInterface
{
    private ConfigRepositoryInterface $configRepository;

    public function __construct(ConfigRepositoryInterface $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @throws SoapException
     */
    public function getApi(string $shopId, string $salesChannelId): DHL24
    {
        /** @var ConfigInterface|null $config */
        $config = $this->configRepository->findOneBy(['shop' => $shopId, 'salesChannelId' => $salesChannelId]);

        if (null === $config) {
            $config = $this->configRepository->findOneBy(['shop' => $shopId, 'salesChannelId' => '']);

            if (null === $config) {
                throw new ConfigNotFoundException('Config not found');
            }
        }

        return new DHL24($config->getUsername(), $config->getPassword(), $config->getAccountNumber(), $config->getSandbox());
    }
}
