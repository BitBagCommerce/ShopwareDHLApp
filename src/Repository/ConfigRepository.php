<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Repository;

use BitBag\ShopwareDHLApp\Entity\Config;
use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use BitBag\ShopwareDHLApp\Exception\ConfigNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class ConfigRepository extends ServiceEntityRepository implements ConfigRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Config::class);
    }

    public function getByShopIdAndSalesChannelId(string $shopId, string $salesChannelId): ConfigInterface
    {
        $config = $this->getByShopIdAndSalesChannelIdQueryBuilder($shopId, $salesChannelId)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $config) {
            $config = $this->getByShopIdAndSalesChannelIdQueryBuilder($shopId, '')
                ->getQuery()
                ->getOneOrNullResult();
        }

        if (null === $config) {
            throw new ConfigNotFoundException('bitbag.shopware_dpd_app.config.credentials_data_not_found');
        }

        return $config;
    }

    public function findByShopIdAndSalesChannelId(string $shopId, string $salesChannelId): ?ConfigInterface
    {
        return $this->getByShopIdAndSalesChannelIdQueryBuilder($shopId, $salesChannelId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function getByShopIdQueryBuilder(string $shopId): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.shop', 'shop')
            ->where('shop.shopId = :shopId')
            ->setParameter('shopId', $shopId);
    }

    private function getByShopIdAndSalesChannelIdQueryBuilder(string $shopId, string $salesChannelId): QueryBuilder
    {
        return $this->getByShopIdQueryBuilder($shopId)
            ->andWhere('c.salesChannelId = :salesChannelId')
            ->setParameter('salesChannelId', $salesChannelId);
    }
}
