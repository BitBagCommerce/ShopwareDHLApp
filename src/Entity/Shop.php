<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Entity;

use BitBag\ShopwareAppSkeleton\Repository\ShopRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShopRepository::class)
 * @ORM\Table(name="shop")
 * @psalm-suppress MissingConstructor
 */
class Shop implements ShopInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected string $shopId;

    /**
     * @ORM\Column(type="string")
     */
    protected string $shopUrl;

    /**
     * @ORM\Column(type="string")
     */
    protected string $shopSecret;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $apiKey;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $secretKey;

    /**
     * @ORM\OneToOne(targetEntity=Config::class, mappedBy="shop", cascade={"persist", "remove"})
     */
    private $config;

    public function getShopId(): string
    {
        return $this->shopId;
    }

    public function setShopId(string $shopId): void
    {
        $this->shopId = $shopId;
    }

    public function getShopUrl(): string
    {
        return $this->shopUrl;
    }

    public function setShopUrl(string $shopUrl): void
    {
        $this->shopUrl = $shopUrl;
    }

    public function getShopSecret(): string
    {
        return $this->shopSecret;
    }

    public function setShopSecret(string $shopSecret): void
    {
        $this->shopSecret = $shopSecret;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function setSecretKey(?string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    public function getConfig(): ?Config
    {
        return $this->config;
    }

    public function setConfig(Config $config): self
    {
        // set the owning side of the relation if necessary
        if ($config->getShop() !== $this) {
            $config->setShop($this);
        }

        $this->config = $config;

        return $this;
    }
}
