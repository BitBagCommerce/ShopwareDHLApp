<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Entity;

use BitBag\ShopwareAppSkeleton\Repository\LabelRepository;
use BitBag\ShopwareAppSystemBundle\Entity\ShopInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LabelRepository::class)
 */
class Label implements LabelInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $orderId;

    /** @ORM\Column(type="string") */
    private string $parcelId;

    /**
     * @ORM\ManyToOne(targetEntity="BitBag\ShopwareAppSystemBundle\Entity\Shop")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="shop_id", onDelete="CASCADE")
     */
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
