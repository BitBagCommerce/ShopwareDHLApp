<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Provider;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

interface NotificationProviderInterface
{
    public function returnNotificationError(string $message, string $shopId): Response;

    public function sign(array $content, string $shopId): JsonResponse;
}
