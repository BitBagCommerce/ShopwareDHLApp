<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Provider;

use BitBag\ShopwareAppSystemBundle\Repository\ShopRepositoryInterface;
use BitBag\ShopwareDHLApp\Exception\ConfigNotFoundException;
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

        $secret = $this->shopRepository->findSecretByShopId($shopId);

        if (null === $secret) {
            throw new ConfigNotFoundException('Secret not found');
        }

        $hmac = hash_hmac('sha256', (string) $response->getContent(), $secret);

        $response->headers->set('shopware-app-signature', $hmac);

        return $response;
    }
}
