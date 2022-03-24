<?php

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;

interface ClientApiServiceInterface
{
    public function getOrder(ClientInterface $client, string $orderId): array;

    public function findDeliveryTimeByMinMax(int $min, int $max, ClientInterface $client): array;

    public function findShippingMethodByShippingKey(ClientInterface $client): array;

    public function findRuleByName(string $name, ClientInterface $client): array;

    public function findRandomRule(ClientInterface $client): array;

    public function findIdsCustomFieldByName(string $name, ClientInterface $client): array;

    public function findCustomFieldSetByName(string $name, ClientInterface $client): array;
}
