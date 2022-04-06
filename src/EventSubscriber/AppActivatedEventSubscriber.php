<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\EventSubscriber;

use BitBag\ShopwareAppSystemBundle\LifecycleEvent\AppActivatedEvent;
use BitBag\ShopwareDHLApp\API\DHL\ClientApiServiceInterface;
use BitBag\ShopwareDHLApp\API\Shopware\AvailabilityRuleCreatorInterface;
use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldsCreatorInterface;
use BitBag\ShopwareDHLApp\Factory\ShippingMethodPayloadFactoryInterface;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

final class AppActivatedEventSubscriber implements EventSubscriberInterface
{
    private CustomFieldsCreatorInterface $customFieldsCreator;

    private ClientApiServiceInterface $apiService;

    private ShippingMethodPayloadFactoryInterface $shippingMethodPayloadFactory;

    private AvailabilityRuleCreatorInterface $availabilityRuleCreator;

    private RepositoryInterface $shippingMethodRepository;

    public function __construct(
        CustomFieldsCreatorInterface $customFieldsCreator,
        ClientApiServiceInterface $apiService,
        ShippingMethodPayloadFactoryInterface $shippingMethodPayloadFactory,
        AvailabilityRuleCreatorInterface $availabilityRuleCreator,
        RepositoryInterface $shippingMethodRepository
    ) {
        $this->customFieldsCreator = $customFieldsCreator;
        $this->apiService = $apiService;
        $this->shippingMethodPayloadFactory = $shippingMethodPayloadFactory;
        $this->availabilityRuleCreator = $availabilityRuleCreator;
        $this->shippingMethodRepository = $shippingMethodRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AppActivatedEvent::class => 'onAppActivated',
        ];
    }

    public function onAppActivated(AppActivatedEvent $event): void
    {
        $context = $event->getContext();
        $this->customFieldsCreator->create($context);
        $this->createShippingMethod($context);
    }

    private function createShippingMethod(Context $context): void
    {
        $shippingMethods = $this->apiService->findShippingMethodByShippingKey($context);

        if (0 !== $shippingMethods->getTotal()) {
            return;
        }

        $deliveryTime = $this->apiService->findDeliveryTimeByMinMax($context, 1, 3);

        $rule = $this->apiService->findRuleByName($context, 'Always valid (Default)');

        if (0 === $rule->getTotal()) {
            $this->availabilityRuleCreator->create($context);
            $rule = $this->apiService->findRuleByName($context, Defaults::AVAILABILITY_RULE);
        }

        $DHLShippingMethod = $this->shippingMethodPayloadFactory->create($rule->firstId() ?? '', $deliveryTime);

        $this->shippingMethodRepository->create($DHLShippingMethod, $context);
    }
}
