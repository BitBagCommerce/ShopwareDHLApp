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
use BitBag\ShopwareAppSystemBundle\Model\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
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

    public function __invoke(
        ActionInterface $action,
        Context $context,
        Request $request
    ): Response {
        $data = $request->toArray();

        $orderId = $data['data']['ids'][0] ?? '';

        $shopId = $action->getSource()->getShopId();

        $label = $this->labelRepository->findByOrderId($orderId, $shopId);

        if (null !== $label) {
            return $this->notificationProvider->returnNotificationError('bitbag.shopware_dhl_app.order.not_found', $shopId);
        }

        /** @var ConfigInterface|null $config */
        $config = $this->configRepository->findOneBy(['shop' => $shopId]);

        if (null === $config) {
            throw new ConfigNotFoundException('Config not found');
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $orderId));
        $criteria->addAssociations(['lineItems.product', 'deliveries']);

        $searchOrder = $this->orderRepository->search($criteria, $context);

        /** @var OrderEntity|null $order */
        $order = $searchOrder->first();

        $totalWeight = $this->countTotalWeight($order->lineItems);

        /** @var string $customerEmail */
        $customerEmail = $order?->orderCustomer?->email;

        if (null === $order?->getCustomFields()) {
            return $this->notificationProvider->returnNotificationError('Fill the package details data.', $shopId);
        }

        if (null === $order->deliveries?->first()->shippingOrderAddress) {
            return $this->notificationProvider->returnNotificationError('Fill the order address.', $shopId);
        }

        $orderData = new OrderData(
            $order->deliveries?->first()->shippingOrderAddress,
            $customerEmail,
            $totalWeight,
            $order?->getCustomFields(),
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
            if (null !== $item->product && null !== $item->product->weight) {
                $weight = $item->quantity * $item->product->weight;
                $totalWeight += $weight;
            }
        }

        return $totalWeight;
    }
}
