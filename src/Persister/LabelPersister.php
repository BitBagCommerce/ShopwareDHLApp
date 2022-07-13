<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Persister;

use BitBag\ShopwareAppSystemBundle\Repository\ShopRepositoryInterface;
use BitBag\ShopwareDHLApp\Entity\Label;
use Doctrine\ORM\EntityManagerInterface;

final class LabelPersister implements LabelPersisterInterface
{
    private ShopRepositoryInterface $shopRepository;

    private EntityManagerInterface $manager;

    public function __construct(ShopRepositoryInterface $shopRepository, EntityManagerInterface $manager)
    {
        $this->shopRepository = $shopRepository;
        $this->manager = $manager;
    }

    public function persist(
        string $shopId,
        int $shipmentId,
        string $orderId,
        string $salesChannelId,
        ): void {
        $shop = $this->shopRepository->getOneByShopId($shopId);

        $label = new Label($orderId, (string) $shipmentId, $salesChannelId, $shop);

        $this->manager->persist($label);
        $this->manager->flush();
    }
}
