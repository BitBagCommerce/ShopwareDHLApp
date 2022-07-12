<?php

namespace BitBag\ShopwareDHLApp\Provider;

interface SplitStreetProviderInterface
{
    public function splitStreet(string $street): array;
}
