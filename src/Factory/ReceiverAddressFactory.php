<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Factory;

use Alexcherniatin\DHL\Structures\ReceiverAddress;
use BitBag\ShopwareDHLApp\Exception\StreetCannotBeSplitException;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use Vin\ShopwareSdk\Data\Entity\OrderAddress\OrderAddressEntity;

final class ReceiverAddressFactory implements ReceiverAddressFactoryInterface
{
    public function create(
        OrderAddressEntity $shippingAddress,
        string $customerEmail,
        array $customFields,
        array $streetAddress
    ): array {

        $shippingAddress->street = $streetAddress[1];
        $houseNumber = $streetAddress[2];

        return (new ReceiverAddress())
            ->setAddressType(ReceiverAddress::ADDRESS_TYPE_B)
            ->setCountry($customFields[Defaults::PACKAGE_COUNTRY_CODE])
            ->setName($shippingAddress->firstName . ' ' . $shippingAddress->lastName)
            ->setPostalCode($shippingAddress->zipcode)
            ->setCity($shippingAddress->city)
            ->setStreet($shippingAddress->street)
            ->setHouseNumber($houseNumber)
            ->setContactPhone($shippingAddress->phoneNumber)
            ->setContactEmail($customerEmail)
            ->structure();
    }
}
