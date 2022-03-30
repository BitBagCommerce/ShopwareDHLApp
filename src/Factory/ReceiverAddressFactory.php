<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use Alexcherniatin\DHL\Exceptions\InvalidStructureException;
use Alexcherniatin\DHL\Structures\ReceiverAddress;

final class ReceiverAddressFactory implements ReceiverAddressFactoryInterface
{
    /**
     * @throws InvalidStructureException
     */
    public function create(array $shippingAddress, string $customerEmail): array
    {
        preg_match('/^([^\d]*[^\d\s]) *(\d.*)$/', $shippingAddress['street'], $street);

        $shippingAddress['street'] = $street[1];
        $shippingAddress['houseNumber'] = $street[2];

        return (new ReceiverAddress())
            ->setAddressType(ReceiverAddress::ADDRESS_TYPE_B)
            ->setCountry('PL')
            ->setName($shippingAddress['firstName'] . ' ' . $shippingAddress['lastName'])
            ->setPostalCode($shippingAddress['zipcode'])
            ->setCity($shippingAddress['city'])
            ->setStreet($shippingAddress['street'])
            ->setHouseNumber($shippingAddress['houseNumber'])
            ->setContactPhone($shippingAddress['phoneNumber'])
            ->setContactEmail($customerEmail)
            ->structure();
    }
}
