<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Persister;

use BitBag\ShopwareAppSkeleton\Entity\Label;
use BitBag\ShopwareAppSystemBundle\Repository\ShopRepositoryInterface;
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
        string $orderId
    ): void {
        $shop = $this->shopRepository->getOneByShopId($shopId);

        $label = new Label($orderId, (string) $shipmentId, $shop);

        $this->manager->persist($label);
        $this->manager->flush();
    }
}
