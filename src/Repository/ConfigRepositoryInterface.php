<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Repository;

use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use Doctrine\Persistence\ObjectRepository;

interface ConfigRepositoryInterface extends ObjectRepository
{
    public function findByShopIdAndSalesChannelId(string $shopId, string $salesChannelId): ?ConfigInterface;
}
