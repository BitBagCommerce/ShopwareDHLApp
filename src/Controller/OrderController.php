<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\API\ShipmentSenderInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\Event\EventInterface;
use Symfony\Component\HttpFoundation\Response;

final class OrderController
{
    private ShipmentSenderInterface $shipment;

    public function __construct(ShipmentSenderInterface $shipment)
    {
        $this->shipment = $shipment;
    }

    public function __invoke(EventInterface $event, ClientInterface $client): Response
    {
        $data = $event->getEventData();

        $orderId = $data['ids'][0];
        $shopId = $event->getShopId();

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

        $this->shipment->createShipments($orderData);

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
