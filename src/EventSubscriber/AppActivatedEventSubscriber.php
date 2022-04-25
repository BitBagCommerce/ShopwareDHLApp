<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\EventSubscriber;

use BitBag\ShopwareAppSystemBundle\LifecycleEvent\AppActivatedEvent;
use BitBag\ShopwareDHLApp\API\Shopware\AvailabilityRuleCreatorInterface;
use BitBag\ShopwareDHLApp\API\Shopware\CustomFieldsCreatorInterface;
use BitBag\ShopwareDHLApp\API\Shopware\ShippingMethodApiServiceInterface;
use BitBag\ShopwareDHLApp\Factory\ShippingMethodPayloadFactoryInterface;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

final class AppActivatedEventSubscriber implements EventSubscriberInterface
{
    private CustomFieldsCreatorInterface $customFieldsCreator;

    private ShippingMethodApiServiceInterface $shippingMethodApiService;

    private ShippingMethodPayloadFactoryInterface $shippingMethodPayloadFactory;

    private AvailabilityRuleCreatorInterface $availabilityRuleCreator;

    private RepositoryInterface $shippingMethodRepository;

    public function __construct(
        CustomFieldsCreatorInterface $customFieldsCreator,
        ShippingMethodApiServiceInterface $shippingMethodApiService,
        ShippingMethodPayloadFactoryInterface $shippingMethodPayloadFactory,
        AvailabilityRuleCreatorInterface $availabilityRuleCreator,
        RepositoryInterface $shippingMethodRepository
    ) {
        $this->customFieldsCreator = $customFieldsCreator;
        $this->shippingMethodApiService = $shippingMethodApiService;
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
        $shippingMethods = $this->shippingMethodApiService->findShippingMethodByShippingKey($context);

        if (0 !== $shippingMethods->getTotal()) {
            return;
        }

        $deliveryTime = $this->shippingMethodApiService->findDeliveryTimeByMinMax(1, 3, $context);

        $rule = $this->shippingMethodApiService->findRuleByName(Defaults::AVAILABILITY_RULE, $context);

        if (0 === $rule->getTotal()) {
            $this->availabilityRuleCreator->create($context);
            $rule = $this->shippingMethodApiService->findRuleByName(Defaults::AVAILABILITY_RULE, $context);
        }

        $DHLShippingMethod = $this->shippingMethodPayloadFactory->create($rule->firstId() ?? '', $deliveryTime);

        $this->shippingMethodRepository->create($DHLShippingMethod, $context);
    }
}
