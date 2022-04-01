<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface ClientApiServiceInterface
{
    public function getOrder(ClientInterface $client, string $orderId): array;

    public function findDeliveryTimeByMinMax(
        ClientInterface $client,
        int $min,
        int $max
    ): array;

    public function findShippingMethodByShippingKey(ClientInterface $client): array;

    public function findRuleByName(ClientInterface $client, string $name): array;

    public function findRandomRule(ClientInterface $client): array;

    public function findCustomFieldIdsByName(ClientInterface $client, string $name): array;

    public function findCustomFieldSetByName(ClientInterface $client, string $name): array;
}