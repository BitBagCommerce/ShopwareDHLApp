<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface ClientApiServiceInterface
{


    public function findDeliveryTimeByMinMax(
        ClientInterface $client,
        int $min,
        int $max
    ): array;

    public function findShippingMethodByShippingKey(ClientInterface $client): array;

    public function findRuleByName(ClientInterface $client, string $name): array;

    public function findCustomFieldIdsByName(ClientInterface $client, string $name): array;

    public function findCustomFieldSetByName(ClientInterface $client, string $name): array;
}
