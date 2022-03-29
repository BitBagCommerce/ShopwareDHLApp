<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Entity;

interface ConfigInterface
{


    public function setShop(Shop $shop): self;

    public function getUsername(): ?string;

    public function getPassword(): ?string;

    public function getAccountNumber(): ?string;

    public function getName();

    public function getPostalCode();

    public function getCity();

    public function getHouseNumber();

    public function getPhoneNumber();

    public function getEmail();

    public function getStreet();
}
