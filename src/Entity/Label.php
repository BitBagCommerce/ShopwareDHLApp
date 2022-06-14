<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Entity;

use BitBag\ShopwareAppSystemBundle\Entity\ShopInterface;
use BitBag\ShopwareDHLApp\Repository\LabelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LabelRepository::class)
 */
class Label implements LabelInterface
{
    private int $id;

    private string $orderId;

    private string $parcelId;

    private ShopInterface $shop;

    public function __construct(
        string $orderId,
        string $parcelId,
        ShopInterface $shop
    ) {
        $this->orderId = $orderId;
        $this->parcelId = $parcelId;
        $this->shop = $shop;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getParcelId(): string
    {
        return $this->parcelId;
    }

    public function getShop(): ShopInterface
    {
        return $this->shop;
    }
}
