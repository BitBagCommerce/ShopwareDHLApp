<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\API\DHL\ShipmentSenderInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\Event\EventInterface;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Exception\ConfigNotFoundException;
use BitBag\ShopwareAppSkeleton\Model\OrderData;
use BitBag\ShopwareAppSkeleton\Provider\NotificationProviderInterface;
use BitBag\ShopwareAppSkeleton\Repository\ConfigRepository;
use BitBag\ShopwareAppSkeleton\Repository\LabelRepository;
use Symfony\Component\HttpFoundation\Response;

final class OrderController
{
    private ShipmentSenderInterface $shipmentSender;

    private ConfigRepository $configRepository;

    private LabelRepository $labelRepository;

    private NotificationProviderInterface $notificationProvider;

    public function __construct(
        ShipmentSenderInterface $shipmentSender,
        ConfigRepository $configRepository,
        LabelRepository $labelRepository,
        NotificationProviderInterface $notificationProvider
    ) {
        $this->shipmentSender = $shipmentSender;
        $this->configRepository = $configRepository;
        $this->labelRepository = $labelRepository;
        $this->notificationProvider = $notificationProvider;
    }

    public function __invoke(EventInterface $event, ClientInterface $client): Response
    {
        $data = $event->getEventData();

        $orderId = $data['ids'][0];
        $shopId = $event->getShopId();

        $label = $this->labelRepository->findByOrderId($orderId, $shopId);

        if (null !== $label) {
            return $this->notificationProvider->returnNotificationError('bitbag.shopware_dhl_app.order.not_found', $shopId);
        }

        /** @var ConfigInterface|null $config */
        $config = $this->configRepository->findOneBy(['shop' => $shopId]);

        if (null === $config) {
            throw new ConfigNotFoundException('Config not found');
        }

        $orderAddressFilter = [
            'filter' => [
                [
                    'type' => 'equals',
                    'field' => 'id',
                    'value' => $orderId,
                ],
            ],
            'associations' => [
                'lineItems' => [
                    'associations' => [
                        'product' => [],
                    ],
                ],
                'deliveries' => [],
            ],
        ];

        $order = $client->search('order', $orderAddressFilter);

        $totalWeight = $this->countTotalWeight($order['data'][0]['lineItems']);

        $orderData = new OrderData(
            $order['data'][0]['deliveries'][0]['shippingOrderAddress'],
            $order['data'][0]['orderCustomer']['email'],
            $totalWeight,
            $order['data'][0]['customFields'],
            $shopId,
            $orderId
        );

        $this->shipmentSender->createShipments($orderData, $config);

        return new Response();
    }

    public function countTotalWeight(array $lineItems): int
    {
        $totalWeight = 0;

        foreach ($lineItems as $item) {
            $weight = $item['quantity'] * $item['product']['weight'];
            $totalWeight += $weight;
        }

        return $totalWeight;
    }
}
