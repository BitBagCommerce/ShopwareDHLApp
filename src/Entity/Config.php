<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Entity;

use BitBag\ShopwareAppSystemBundle\Entity\ShopInterface;
use BitBag\ShopwareDHLApp\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 */
class Config implements ConfigInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity="BitBag\ShopwareAppSystemBundle\Entity\Shop", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="shop_id", onDelete="CASCADE")
     */
    private ShopInterface $shop;

    /** @ORM\Column(type="string", length=255) */
    private string $username;

    /** @ORM\Column(type="string", length=255) */
    private string $password;

    /** @ORM\Column(type="string", length=255) */
    private string $accountNumber;

    /** @ORM\Column(type="string", length=255) */
    private string $name;

    /** @ORM\Column(type="string", length=255) */
    private string $postalCode;

    /** @ORM\Column(type="string", length=255) */
    private string $city;

    /** @ORM\Column(type="string", length=255) */
    private string $street;

    /** @ORM\Column(type="string", length=255) */
    private string $houseNumber;

    /** @ORM\Column(type="string", length=255) */
    private string $phoneNumber;

    /** @ORM\Column(type="string", length=255) */
    private string $email;

    /** @ORM\Column(type="string", length=255) */
    private string $payerType;

    /** @ORM\Column(type="string", length=255) */
    private string $paymentMethod;

    public function getId(): int
    {
        return $this->id;
    }

    public function getShop(): ShopInterface
    {
        return $this->shop;
    }

    public function setShop(ShopInterface $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(string $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getPayerType(): string
    {
        return $this->payerType;
    }

    public function setPayerType(string $payerType): void
    {
        $this->payerType = $payerType;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }
}
