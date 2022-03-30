<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use Alexcherniatin\DHL\Exceptions\InvalidStructureException;
use Alexcherniatin\DHL\Structures\Address;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;

final class SenderAddressFactory implements SenderAddressFactoryInterface
{
    /**
     * @throws InvalidStructureException
     */
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
