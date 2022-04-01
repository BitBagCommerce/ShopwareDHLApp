<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\AppSystem\Event\EventInterface;
use BitBag\ShopwareAppSkeleton\Provider\NotificationProviderInterface;
use BitBag\ShopwareAppSkeleton\Repository\LabelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class GetLabelController extends AbstractController
{
    private LabelRepository $labelRepository;

    private TranslatorInterface $translator;

    private NotificationProviderInterface $notificationProvider;

    public function __construct(
        LabelRepository $labelRepository,
        TranslatorInterface $translator,
        NotificationProviderInterface $notificationProvider
    ) {
        $this->labelRepository = $labelRepository;
        $this->translator = $translator;
        $this->notificationProvider = $notificationProvider;
    }

    public function __invoke(EventInterface $event): JsonResponse
    {
        $data = $event->getEventData();
        $shopId = $event->getShopId();

        $orderId = $data['ids'][0];

        $label = $this->labelRepository->findByOrderId($orderId, $shopId);

        if (null === $label) {
            return $this->$this->notificationProvider->returnNotificationError($this->translator->trans('bitbag.shopware_dhl_app.order.not_found'), $shopId);
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

        return $this->notificationProvider->sign($response, $shopId);
    }
}
