<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\API\DHL\ShipmentSenderInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\Event\EventInterface;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Exception\ConfigNotFoundException;
use BitBag\ShopwareAppSkeleton\Model\OrderData;
use BitBag\ShopwareAppSkeleton\Repository\ConfigRepository;
use Symfony\Component\HttpFoundation\Response;

final class OrderController
{
    private ShipmentSenderInterface $shipmentSender;

    private ConfigRepository $configRepository;

    public function __construct(ShipmentSenderInterface $shipmentSender, ConfigRepository $configRepository)
    {
        $this->shipmentSender = $shipmentSender;
        $this->configRepository = $configRepository;
    }

    public function __invoke(EventInterface $event, ClientInterface $client): Response
    {
        $data = $event->getEventData();

        $orderId = $data['ids'][0];
        $shopId = $event->getShopId();

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

        $orderData = [
            'shippingAddress' => $order['data'][0]['deliveries'][0]['shippingOrderAddress'],
            'customerEmail' => $order['data'][0]['orderCustomer']['email'],
            'totalWeight' => $totalWeight,
            'customFields' => $order['data'][0]['customFields'],
            'shopId' => $shopId,
        ];

        $orderData = new OrderData(
            $order['data'][0]['deliveries'][0]['shippingOrderAddress'],
            $order['data'][0]['orderCustomer']['email'],
            $totalWeight,
            $order['data'][0]['customFields'],
            $shopId
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
