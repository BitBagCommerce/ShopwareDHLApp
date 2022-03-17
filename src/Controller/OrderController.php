<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\API\CreateShipmentInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class OrderController
{
    private CreateShipmentInterface $shipment;

    public function __construct(CreateShipmentInterface $shipment)
    {
        $this->shipment = $shipment;
    }

    public function __invoke(ClientInterface $client, Request $request): Response
    {
        $data = json_decode($request->getContent());

        $orderId = $data->data->ids[0];
        $shopId = $data->source->shopId;

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
        $lineItems = $order['data'][0]['lineItems'];
        $customerEmail = $order['data'][0]['orderCustomer']['email'];

        $totalWeight = 0;

        foreach ($lineItems as $item) {
            $weight = $item['quantity'] * $item['product']['weight'];
            $totalWeight += $weight;
        }

        $shippingAddress = $order['data'][0]['deliveries'][0]['shippingOrderAddress'];

        $this->shipment->createShipments($shippingAddress, $shopId, $customerEmail);

        return new Response();
    }
}
