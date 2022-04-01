<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Provider;

use BitBag\ShopwareAppSkeleton\Repository\ShopRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class NotificationProvider implements NotificationProviderInterface
{
    private TranslatorInterface $translator;

    private ShopRepositoryInterface $shopRepository;

    public function __construct(TranslatorInterface $translator, ShopRepositoryInterface $shopRepository)
    {
        $this->translator = $translator;
        $this->shopRepository = $shopRepository;
    }

    public function returnNotificationError(string $message, string $shopId): Response
    {
        $response = [
            'actionType' => 'notification',
            'payload' => [
                'status' => 'error',
                'message' => $this->translator->trans($message),
            ],
        ];

        return $this->sign($response, $shopId);
    }

    public function sign(array $content, string $shopId): JsonResponse
    {
        $response = new JsonResponse($content);

        $secret = $this->getSecretByShopId($shopId);

        $hmac = hash_hmac('sha256', (string) $response->getContent(), $secret);

        $response->headers->set('shopware-app-signature', $hmac);

        return $response;
    }

    public function getSecretByShopId(string $shopId): string
    {
        return (string) $this->shopRepository->findSecretByShopId($shopId);
    }
}
