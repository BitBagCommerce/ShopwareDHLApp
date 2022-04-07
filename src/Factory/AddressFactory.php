<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use Alexcherniatin\DHL\Structures\Address;
use BitBag\ShopwareDHLApp\Entity\ConfigInterface;

final class AddressFactory implements AddressFactoryInterface
{
    public function create(ConfigInterface $config): array
    {
        return (new Address())
            ->setName($config->getName())
            ->setPostalCode($config->getPostalCode())
            ->setCity($config->getCity())
            ->setStreet($config->getStreet())
            ->setHouseNumber($config->getHouseNumber())
            ->setContactPhone($config->getPhoneNumber())
            ->setContactEmail($config->getEmail())
            ->structure();
    }
}
