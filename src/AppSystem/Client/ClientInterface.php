<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\AppSystem\Client;

interface ClientInterface
{


    public function search(string $entityType, array $criteria): array;

    public function searchIds(string $entityType, array $criteria): array;

    public function createEntity(string $entityType, array $entityData): void;
}
