<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Entity;

interface ConfigInterface
{
    public function getId(): int;

    public function getShop(): ShopInterface;

    public function setShop(ShopInterface $shop): self;

    public function getUsername(): string;

    public function setUsername(string $username): self;

    public function getPassword(): string;

    public function setPassword(string $password): self;

    public function getAccountNumber(): string;

    public function setAccountNumber(string $accountNumber): self;

    public function getName(): string;

    public function setName(string $name): void;

    public function getPostalCode(): string;

    public function setPostalCode(string $postalCode): void;

    public function getCity(): string;

    public function setCity(string $city): void;

    public function getHouseNumber(): string;

    public function setHouseNumber(string $houseNumber): void;

    public function getPhoneNumber(): string;

    public function setPhoneNumber(string $phoneNumber): void;

    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function getStreet(): string;

    public function setStreet(string $street): void;

    public function getPayerType(): string;

    public function setPayerType(string $payerType): void;
}
