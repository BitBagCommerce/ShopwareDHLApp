<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\Provider\NotificationProviderInterface;
use BitBag\ShopwareAppSkeleton\Repository\LabelRepository;
use BitBag\ShopwareAppSystemBundle\Event\EventInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

    public function __invoke(EventInterface $event): Response
    {
        $shopId = $event->getShopId();

        $orderId = $event->getSingleEventData()->getPrimaryKey();

        $label = $this->labelRepository->findByOrderId($orderId, $shopId);

        if (null === $label) {
            return $this->notificationProvider->returnNotificationError($this->translator->trans('bitbag.shopware_dhl_app.order.not_found'), $shopId);
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
