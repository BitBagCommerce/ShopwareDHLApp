<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Controller;

use BitBag\ShopwareAppSystemBundle\Model\Action\ActionInterface;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Error;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Success;
use BitBag\ShopwareAppSystemBundle\Response\FeedbackResponse;
use BitBag\ShopwareDHLApp\API\DHL\ShipmentApiServiceInterface;
use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use BitBag\ShopwareDHLApp\Model\OrderData;
use BitBag\ShopwareDHLApp\Persister\LabelPersisterInterface;
use BitBag\ShopwareDHLApp\Repository\ConfigRepository;
use BitBag\ShopwareDHLApp\Repository\LabelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
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

    private LabelPersisterInterface $labelPersister;

    private RepositoryInterface $orderRepository;

    private TranslatorInterface $translator;

    public function __construct(
        ShipmentApiServiceInterface $shipmentApiService,
        ConfigRepository $configRepository,
        LabelRepository $labelRepository,
        LabelPersisterInterface $labelPersister,
        RepositoryInterface $orderRepository,
        TranslatorInterface $translator
    ) {
        $this->shipmentApiService = $shipmentApiService;
        $this->configRepository = $configRepository;
        $this->labelRepository = $labelRepository;
        $this->labelPersister = $labelPersister;
        $this->orderRepository = $orderRepository;
        $this->translator = $translator;
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
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.not_found')));
        }

        /** @var ConfigInterface|null $config */
        $config = $this->configRepository->findOneBy(['shop' => $shopId]);

        if (null === $config) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.config.not_found')));
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $orderId));
        $criteria->addAssociations(['lineItems.product', 'deliveries', 'deliveries.shippingMethod']);

        $searchOrder = $this->orderRepository->search($criteria, $context);

        /** @var OrderEntity|null $order */
        $order = $searchOrder->first();

        $totalWeight = $this->countTotalWeight($order->lineItems);

        /** @var string $customerEmail */
        $customerEmail = $order?->orderCustomer?->email;

        if (null === $order?->getCustomFields()) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.empty_package_details')));
        }

        if (null === $order->deliveries?->first()->shippingOrderAddress) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.empty_order_address')));
        }

        if (null === $order->deliveries?->first()?->shippingOrderAddress->phoneNumber) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.empty_phone_number')));
        }

        if ('DHL' !== $order->deliveries?->first()?->shippingMethod->name) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.not_for_dhl')));
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

        return new FeedbackResponse(new Success($this->translator->trans('bitbag.shopware_dhl_app.order.created')));
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
