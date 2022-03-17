<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Entity;

interface ConfigInterface
{
    public function getId(): ?int;

    public function getShop(): ?Shop;

    public function setShop(Shop $shop): self;

    public function getUsername(): ?string;

    public function setUsername(string $username): self;

    public function getPassword(): ?string;

    public function setPassword(string $password): self;

    public function getAccountNumber(): ?string;

    public function setAccountNumber(string $accountNumber): self;

    public function getName();

    public function setName($name): void;

    public function getPostalCode();

    public function setPostalCode($postalCode): void;

    public function getCity();

    public function setCity($city): void;

    public function getHouseNumber();

    public function setHouseNumber($houseNumber): void;

    public function getPhoneNumber();

    public function setPhoneNumber($phoneNumber): void;

    public function getEmail();

    public function setEmail($email): void;

    public function getStreet();

    public function setStreet($street): void;
}