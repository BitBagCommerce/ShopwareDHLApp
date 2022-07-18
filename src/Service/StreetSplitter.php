<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Service;

use BitBag\ShopwareDHLApp\Exception\StreetCannotBeSplitException;

final class StreetSplitter implements StreetSplitterInterface
{
    public function splitStreet(string $street): array
    {
        if (!preg_match('/^(.+?) *(\d.*)$/', $street, $streetAddress)) {
            throw new StreetCannotBeSplitException('bitbag.shopware_dhl_app.order.invalid_street');
        }

        return $streetAddress;
    }
}
