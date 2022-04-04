<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\API\DHL\ShipmentApiServiceInterface;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Exception\ConfigNotFoundException;
use BitBag\ShopwareAppSkeleton\Model\OrderData;
use BitBag\ShopwareAppSkeleton\Persister\LabelPersisterInterface;
use BitBag\ShopwareAppSkeleton\Provider\NotificationProviderInterface;
use BitBag\ShopwareAppSkeleton\Repository\ConfigRepository;
use BitBag\ShopwareAppSkeleton\Repository\LabelRepository;
use BitBag\ShopwareAppSystemBundle\Event\EventInterface;
use Symfony\Component\HttpFoundation\Response;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Criteria;
use Vin\ShopwareSdk\Data\Entity\Order\OrderEntity;
use Vin\ShopwareSdk\Data\Entity\OrderLineItem\OrderLineItemCollection;
use Vin\ShopwareSdk\Data\Filter\EqualsFilter;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

final class OrderController
{
    private ShipmentApiServiceInterface $shipmentApiService;

    private ConfigRepository $configRepository;

    private LabelRepository $labelRepository;

    private NotificationProviderInterface $notificationProvider;

    private LabelPersisterInterface $labelPersister;

    private RepositoryInterface $orderRepository;

    public function __construct(
        ShipmentApiServiceInterface $shipmentApiService,
        ConfigRepository $configRepository,
        LabelRepository $labelRepository,
        NotificationProviderInterface $notificationProvider,
        LabelPersisterInterface $labelPersister,
        RepositoryInterface $orderRepository
    ) {
        $this->shipmentApiService = $shipmentApiService;
        $this->configRepository = $configRepository;
        $this->labelRepository = $labelRepository;
        $this->notificationProvider = $notificationProvider;
        $this->labelPersister = $labelPersister;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(EventInterface $event, Context $context): Response
    {
        $orderId = $event->getSingleEventData()->getPrimaryKey();
        $shopId = $event->getShopId();

        $label = $this->labelRepository->findByOrderId($orderId ?? '', $shopId);

        if (null !== $label) {
            return $this->notificationProvider->returnNotificationError('bitbag.shopware_dhl_app.order.not_found', $shopId);
        }

        /** @var ConfigInterface|null $config */
        $config = $this->configRepository->findOneBy(['shop' => $shopId]);

        if (null === $config) {
            throw new ConfigNotFoundException('Config not found');
        }

        /*        $orderAddressFilter = [
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

                $order = $client->search('order', $orderAddressFilter);*/

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $orderId));
        $criteria->addAssociations(['lineItems.product', 'deliveries']);

        /** @var OrderEntity $order */
        $order = $this->orderRepository->search($criteria, $context);

        $totalWeight = $this->countTotalWeight($order->lineItems);
        /* $totalWeight = $this->countTotalWeight($order['data'][0]['lineItems']);

         $orderData = new OrderData(
             $order['data'][0]['deliveries'][0]['shippingOrderAddress'],
             $order['data'][0]['orderCustomer']['email'],
             $totalWeight,
             $order['data'][0]['customFields'],
             $shopId,
             $orderId
         );*/

        $orderData = new OrderData(
            $order->deliveries->first()->shippingOrderAddress,
            $order->orderCustomer->email,
            $totalWeight,
            $order->getCustomFields(),
            $shopId,
            $orderId
        );

        $shipment = $this->shipmentApiService->createShipments($orderData, $config);

        $this->labelPersister->persist($orderData->getShopId(), $shipment['shipmentId'], $orderData->getOrderId());

        return new Response();
    }

    public function countTotalWeight(OrderLineItemCollection $lineItems): float
    {
        $totalWeight = 0.0;

        foreach ($lineItems as $item) {
            $weight = $item->quantity * $item->product->weight;
            $totalWeight += $weight;
        }

        return $totalWeight;
    }
}
