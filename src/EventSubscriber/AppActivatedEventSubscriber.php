<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\EventSubscriber;

use BitBag\ShopwareAppSkeleton\API\CreateCustomFieldsInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\LifecycleEvent\AppActivatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class AppActivatedEventSubscriber implements EventSubscriberInterface
{
    private CreateCustomFieldsInterface $createCustomFields;

    public function __construct(CreateCustomFieldsInterface $createCustomFields)
    {
        $this->createCustomFields = $createCustomFields;
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
        /*        $client = $event->getClient();

                $filterForShippingMethod = [
                    'filter' => [
                        [
                            'type' => 'contains',
                            'field' => 'name',
                            'value' => 'DHL',
                        ],
                    ],
                ];

                $filterForDeliveryTime = [
                    'filter' => [
                        [
                            'type' => 'contains',
                            'field' => 'unit',
                            'value' => 'day',
                        ],
                        [
                            'type' => 'equals',
                            'field' => 'min',
                            'value' => 1,
                        ],
                        [
                            'type' => 'equals',
                            'field' => 'max',
                            'value' => 3,
                        ],
                    ],
                ];

                $filterForAvailabilityRule = [
                    'filter' => [
                        [
                            'type' => 'equals',
                            'field' => 'name',
                            'value' => 'Cart >= 0',
                        ],
                    ],
                ];

                $shippingMethods = $client->searchIds('shipping-method', $filterForShippingMethod);

                if ($shippingMethods['total']) {
                    return;
                }

                $deliveryTime = $client->searchIds('delivery-time', $filterForDeliveryTime);

                $availabilityRule = $client->searchIds('rule', $filterForAvailabilityRule);

                if (!$availabilityRule['total']) {
                    $availabilityRule = $client->searchIds('rule', []);
                }

                $DHLShippingMethod = [
                    'name' => 'DHL',
                    'active' => true,
                    'description' => 'DHL shipping method',
                    'taxType' => 'auto',
                    'translated' => [
                        'name' => 'DHL',
                    ],
                    'availabilityRuleId' => $availabilityRule['data'][0],
                    'createdAt' => '2022-01-20T11:14:37.415+00:00',
                ];

                if (isset($deliveryTime['total']) && $deliveryTime['total'] > 0) {
                    $DHLShippingMethod = array_merge($DHLShippingMethod, [
                        'deliveryTimeId' => $deliveryTime['data'][0],
                    ]);
                } else {
                    $DHLShippingMethod = array_merge($DHLShippingMethod, ['deliveryTime' => [
                        'name' => '1-3 days',
                        'min' => 1,
                        'max' => 3,
                        'unit' => 'day',
                        'createdAt' => '2022-01-20T11:14:37.000+00:00',
                    ]]);
                }

                $client->createEntity('shipping-method', $DHLShippingMethod);*/

        //$this->createShippingMethod($client);
    }

    private function createShippingMethod(ClientInterface $client): void
    {
        $shippingMethods = $this->clientApiService->findShippingMethodByShippingKey($client);

        if ($shippingMethods['total']) {
            return;
        }

        $deliveryTime = $this->clientApiService->findDeliveryTimeByMinMax(1, 3, $client);

        $rule = $this->clientApiService->findRuleByName('Cart >= 0', $client);

        if (!$rule) {
            $rule = $this->clientApiService->findRandomRule($client);
        }

        $this->createShippingMethodFactory->create($rule['data'][0], $deliveryTime, $client);
    }
}
