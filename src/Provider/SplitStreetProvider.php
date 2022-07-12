<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Provider;

use BitBag\ShopwareDHLApp\Exception\StreetCannotBeSplitException;

final class SplitStreetProvider implements SplitStreetProviderInterface
{
    public function splitStreet(string $street): array
    {
        if (!preg_match('/^(\w[^\d]*[^\d\s]) *(\d.*)$/', $street, $streetAddress)) {
            throw new StreetCannotBeSplitException('bitbag.shopware_dhl_app.order.invalid_street');
        }

        return $streetAddress;
    }
}
