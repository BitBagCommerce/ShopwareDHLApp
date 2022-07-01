<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Controller;

use BitBag\ShopwareAppSystemBundle\Model\Action\ActionInterface;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Error;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Success;
use BitBag\ShopwareAppSystemBundle\Response\FeedbackResponse;
use BitBag\ShopwareDHLApp\API\DHL\ShipmentApiServiceInterface;
use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use BitBag\ShopwareDHLApp\Exception\StreetCannotBeSplitException;
use BitBag\ShopwareDHLApp\Model\OrderData;
use BitBag\ShopwareDHLApp\Persister\LabelPersisterInterface;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use BitBag\ShopwareDHLApp\Repository\ConfigRepository;
use BitBag\ShopwareDHLApp\Repository\LabelRepository;
use SoapFault;
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

        $customFields = $order?->getCustomFields();
        $totalWeight = $this->countTotalWeight($order->lineItems);

        if (0.0 === $totalWeight) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.empty_weight')));
        }

        /** @var string $customerEmail */
        $customerEmail = $order?->orderCustomer?->email;

        if (null === $customFields) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.empty_package_details')));
        }

        if (null === $order->deliveries?->first()->shippingOrderAddress) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.empty_order_address')));
        }

        if (null === $order->deliveries?->first()?->shippingOrderAddress->phoneNumber) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.empty_phone_number')));
        }

        if ('DHL' !== $order->deliveries?->first()?->shippingMethod?->name) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.not_for_dhl')));
        }

        if (null === $customFields[Defaults::PACKAGE_COUNTRY_CODE]) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.empty_country_code')));
        }

        if (
            0 === $customFields[Defaults::PACKAGE_DEPTH] ||
            0 === $customFields[Defaults::PACKAGE_HEIGHT] ||
            0 === $customFields[Defaults::PACKAGE_WIDTH]
        ) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.empty_package_dimensions')));
        }

        if (!preg_match('/[0-9][0-9][-][0-9][0-9][0-9]/', $order->deliveries?->first()->shippingOrderAddress?->zipcode)) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.invalid_zipcode')));
        }

        $street = $this->splitStreet($order->deliveries?->first()->shippingOrderAddress?->street);

        $orderData = new OrderData(
            $order->deliveries?->first()->shippingOrderAddress,
            $customerEmail,
            $totalWeight,
            $customFields,
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

    private function splitStreet(string $street): array
    {
        if (!preg_match('/^([^\d]*[^\d\s]) *(\d.*)$/', $street, $streetAddress)) {
            throw new StreetCannotBeSplitException('bitbag.shopware_dhl_app.order.invalid_street');
        }

        return $streetAddress;
    }
}
