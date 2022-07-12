<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Provider;

interface SplitStreetProviderInterface
{
    public function splitStreet(string $street): array;
}
