<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Saver;

use BitBag\ShopwareAppSkeleton\Entity\Label;
use BitBag\ShopwareAppSkeleton\Repository\ShopRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class LabelSaver implements LabelSaverInterface
{
    private ShopRepositoryInterface $shopRepository;

    private EntityManagerInterface $manager;

    public function __construct(ShopRepositoryInterface $shopRepository, EntityManagerInterface $manager)
    {
        $this->shopRepository = $shopRepository;
        $this->manager = $manager;
    }

    public function save(
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
