<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\AppSystem\Event\EventInterface;
use BitBag\ShopwareAppSkeleton\Repository\LabelRepository;
use BitBag\ShopwareAppSkeleton\Repository\ShopRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class GetLabelController extends AbstractController
{
    private LabelRepository $labelRepository;

    private TranslatorInterface $translator;

    private ShopRepositoryInterface $shopRepository;

    public function __construct(
        LabelRepository $labelRepository,
        TranslatorInterface $translator,
        ShopRepositoryInterface $shopRepository
    ) {
        $this->labelRepository = $labelRepository;
        $this->translator = $translator;
        $this->shopRepository = $shopRepository;
    }

    public function __invoke(EventInterface $event)
    {
        $data = $event->getEventData();
        $shopId = $event->getShopId();

        $orderId = $data['ids'][0];

        $label = $this->labelRepository->findByOrderId($orderId, $shopId);

        if (null === $label) {
            return $this->returnNotificationError($this->translator->trans('bitbag.shopware_dhl_app.order.not_found'), $shopId);
        }

        $redirectUrl = $this->generateUrl(
            'show_label',
            ['orderId' => $orderId],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $response = [
            'actionType' => 'openNewTab',
            'payload' => [
                'redirectUrl' => $redirectUrl,
            ],
        ];

        return $this->sign($response, $shopId);
    }

    private function returnNotificationError(string $message, string $shopId): Response
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

    private function sign(array $content, string $shopId): JsonResponse
    {
        $response = new JsonResponse($content);

        $secret = $this->getSecretByShopId($shopId);

        $hmac = hash_hmac('sha256', (string) $response->getContent(), $secret);

        $response->headers->set('shopware-app-signature', $hmac);

        return $response;
    }

    private function getSecretByShopId(string $shopId): string
    {
        return (string) $this->shopRepository->findSecretByShopId($shopId);
    }
}
