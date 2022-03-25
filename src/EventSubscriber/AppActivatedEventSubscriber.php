<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\EventSubscriber;

use BitBag\ShopwareAppSkeleton\API\ClientApiServiceInterface;
use BitBag\ShopwareAppSkeleton\API\CustomFieldsCreatorInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\LifecycleEvent\AppActivatedEvent;
use BitBag\ShopwareAppSkeleton\Factory\ShippingMethodPayloadFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class AppActivatedEventSubscriber implements EventSubscriberInterface
{
    private CustomFieldsCreatorInterface $createCustomFields;
    private ClientApiServiceInterface $apiService;
    private ShippingMethodPayloadFactoryInterface $createShippingMethodFactory;

    public function __construct(
        CustomFieldsCreatorInterface         $createCustomFields,
        ClientApiServiceInterface            $apiService,
        ShippingMethodPayloadFactoryInterface $createShippingMethodFactory
    ) {
        $this->createCustomFields = $createCustomFields;
        $this->apiService = $apiService;
        $this->createShippingMethodFactory = $createShippingMethodFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AppActivatedEvent::class => 'onAppActivated',
        ];
    }

    public function onAppActivated(AppActivatedEvent $event): void
    {
        $this->createCustomFields->create($event->getClient());
        $this->createShippingMethod($event->getClient());
    }

    private function createShippingMethod(ClientInterface $client): void
    {
        $shippingMethods = $this->apiService->findShippingMethodByShippingKey($client);

        if (0 === $shippingMethods['total']) {
            return;
        }

        $deliveryTime = $this->apiService->findDeliveryTimeByMinMax($client, 1, 3);

        $rule = $this->apiService->findRuleByName($client, 'Cart >= 0');

        if (0 === $rule['total']) {
            $rule = $this->apiService->findRandomRule($client);
        }

        $DHLShippingMethod = $this->createShippingMethodFactory->create($rule['data'][0], $deliveryTime);

        $client->createEntity('shipping-method', $DHLShippingMethod);
    }
}
