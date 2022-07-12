<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Controller;

use BitBag\ShopwareAppSystemBundle\Model\Action\ActionInterface;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Error;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Success;
use BitBag\ShopwareAppSystemBundle\Response\FeedbackResponse;
use BitBag\ShopwareDHLApp\API\DHL\ShipmentApiServiceInterface;
use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use BitBag\ShopwareDHLApp\Exception\OrderException;
use BitBag\ShopwareDHLApp\Exception\PackageDetailsException;
use BitBag\ShopwareDHLApp\Exception\StreetCannotBeSplitException;
use BitBag\ShopwareDHLApp\Model\OrderData;
use BitBag\ShopwareDHLApp\Persister\LabelPersisterInterface;
use BitBag\ShopwareDHLApp\Provider\SplitStreetProviderInterface;
use BitBag\ShopwareDHLApp\Repository\ConfigRepository;
use BitBag\ShopwareDHLApp\Repository\LabelRepository;
use BitBag\ShopwareDHLApp\Validator\OrderValidatorInterface;
use SoapFault;
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

    private OrderValidatorInterface $orderValidator;

    private SplitStreetProviderInterface $splitStreetProvider;

    public function __construct(
        ShipmentApiServiceInterface $shipmentApiService,
        ConfigRepository $configRepository,
        LabelRepository $labelRepository,
        LabelPersisterInterface $labelPersister,
        RepositoryInterface $orderRepository,
        TranslatorInterface $translator,
        OrderValidatorInterface $orderValidator,
        SplitStreetProviderInterface $splitStreetProvider
    ) {
        $this->shipmentApiService = $shipmentApiService;
        $this->configRepository = $configRepository;
        $this->labelRepository = $labelRepository;
        $this->labelPersister = $labelPersister;
        $this->orderRepository = $orderRepository;
        $this->translator = $translator;
        $this->orderValidator = $orderValidator;
        $this->splitStreetProvider = $splitStreetProvider;
    }

    public function __invoke(
        ActionInterface $action,
        Context $context,
    ): Response {
        $orderId = $action->getData()->getIds()[0] ?? '';

        $shopId = $action->getSource()->getShopId();

        $label = $this->labelRepository->findByOrderId($orderId, $shopId);

        if (null !== $label) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.already_exists')));
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

        try {
            $this->orderValidator->validate($order);
        } catch (OrderException|PackageDetailsException $e) {
            return new FeedbackResponse(new Error($this->translator->trans($e->getMessage())));
        }

        $totalWeight = $this->countTotalWeight($order->lineItems);

        if (0.0 === $totalWeight) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.empty_weight')));
        }

        /** @var string $customerEmail */
        $customerEmail = $order->orderCustomer?->email;

        try {
            $street = $this->splitStreetProvider->splitStreet($order->deliveries?->first()->shippingOrderAddress?->street);
        } catch (StreetCannotBeSplitException $e) {
            return new FeedbackResponse(new Error($this->translator->trans($e->getMessage())));
        }

        $orderData = new OrderData(
            $order->deliveries?->first()->shippingOrderAddress,
            $customerEmail,
            $totalWeight,
            $order->getCustomFields(),
            $shopId,
            $orderId,
            $street
        );

        try {
            $shipment = $this->shipmentApiService->createShipments($orderData, $config);
        } catch (SoapFault $e) {
            return new FeedbackResponse(new Error($this->translator->trans($e->getMessage(), [], 'api')));
        }

        $this->labelPersister->persist($orderData->getShopId(), $shipment['shipmentId'], $orderData->getOrderId());

        return new FeedbackResponse(new Success($this->translator->trans('bitbag.shopware_dhl_app.order.created')));
    }

    private function countTotalWeight(OrderLineItemCollection $lineItems): float
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
