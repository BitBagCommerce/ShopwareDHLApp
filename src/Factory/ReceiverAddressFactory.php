<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use Alexcherniatin\DHL\Structures\ReceiverAddress;
use BitBag\ShopwareAppSkeleton\Exception\StreetCannotBeSplitException;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

final class ReceiverAddressFactory implements ReceiverAddressFactoryInterface
{
    public function create(
        array $shippingAddress,
        string $customerEmail,
        array $customFields
    ): array {
        $streetAddress = $this->splitStreet($shippingAddress['street']);

        $shippingAddress['street'] = $streetAddress[1];
        $shippingAddress['houseNumber'] = $streetAddress[2];

        return (new ReceiverAddress())
            ->setAddressType(ReceiverAddress::ADDRESS_TYPE_B)
            ->setCountry($customFields[Defaults::PACKAGE_COUNTRY_CODE])
            ->setName($shippingAddress['firstName'] . ' ' . $shippingAddress['lastName'])
            ->setPostalCode($shippingAddress['zipcode'])
            ->setCity($shippingAddress['city'])
            ->setStreet($shippingAddress['street'])
            ->setHouseNumber($shippingAddress['houseNumber'])
            ->setContactPhone($shippingAddress['phoneNumber'])
            ->setContactEmail($customerEmail)
            ->structure();
    }

    private function splitStreet(string $street): array
    {
        if (!preg_match('/^([^\d]*[^\d\s]) *(\d.*)$/', $street, $streetAddress)) {
            throw new StreetCannotBeSplitException('Street cannot be split');
        }

        return $streetAddress;
    }
}
