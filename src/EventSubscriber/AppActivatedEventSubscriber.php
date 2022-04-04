<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\EventSubscriber;

use BitBag\ShopwareAppSkeleton\API\DHL\ClientApiServiceInterface;
use BitBag\ShopwareAppSkeleton\API\Shopware\AvailabilityRuleCreatorInterface;
use BitBag\ShopwareAppSkeleton\API\Shopware\CustomFieldsCreatorInterface;
use BitBag\ShopwareAppSkeleton\Factory\ShippingMethodPayloadFactoryInterface;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;
use BitBag\ShopwareAppSystemBundle\LifecycleEvent\AppActivatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

final class AppActivatedEventSubscriber implements EventSubscriberInterface
{
    private CustomFieldsCreatorInterface $createCustomFields;

    private ClientApiServiceInterface $apiService;

    private ShippingMethodPayloadFactoryInterface $createShippingMethodFactory;

    private AvailabilityRuleCreatorInterface $availabilityRuleCreator;

    private RepositoryInterface $shippingMethodRepository;

    public function __construct(
        CustomFieldsCreatorInterface $createCustomFields,
        ClientApiServiceInterface $apiService,
        ShippingMethodPayloadFactoryInterface $createShippingMethodFactory,
        AvailabilityRuleCreatorInterface $availabilityRuleCreator,
        RepositoryInterface $shippingMethodRepository
    ) {
        $this->createCustomFields = $createCustomFields;
        $this->apiService = $apiService;
        $this->createShippingMethodFactory = $createShippingMethodFactory;
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
        $this->createCustomFields->create($context);
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

        $DHLShippingMethod = $this->createShippingMethodFactory->create($rule->firstId() ?? '', $deliveryTime);

        $this->shippingMethodRepository->create($DHLShippingMethod, $context);
    }
}
