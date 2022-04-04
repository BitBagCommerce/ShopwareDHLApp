<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use BitBag\ShopwareAppSkeleton\Provider\Defaults;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Criteria;
use Vin\ShopwareSdk\Data\Filter\ContainsFilter;
use Vin\ShopwareSdk\Data\Filter\EqualsFilter;
use Vin\ShopwareSdk\Repository\RepositoryInterface;
use Vin\ShopwareSdk\Repository\Struct\EntitySearchResult;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

final class ClientApiService implements ClientApiServiceInterface
{
    private RepositoryInterface $shippingMethodRepository;

    private RepositoryInterface $orderRepository;

    private RepositoryInterface $deliveryTimeRepository;

    private RepositoryInterface $ruleRepository;

    private RepositoryInterface $customFieldRepository;

    private RepositoryInterface $customFieldSetRepository;

    public function __construct(
        RepositoryInterface $shippingMethodRepository,
        RepositoryInterface $orderRepository,
        RepositoryInterface $deliveryTimeRepository,
        RepositoryInterface $ruleRepository,
        RepositoryInterface $customFieldRepository,
        RepositoryInterface $customFieldSetRepository
    ) {
        $this->shippingMethodRepository = $shippingMethodRepository;
        $this->orderRepository = $orderRepository;
        $this->deliveryTimeRepository = $deliveryTimeRepository;
        $this->ruleRepository = $ruleRepository;
        $this->customFieldRepository = $customFieldRepository;
        $this->customFieldSetRepository = $customFieldSetRepository;
    }

    public function getOrder(Context $context, string $orderId): EntitySearchResult
    {
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
                        'deliveries' => [
                            'associations' => [
                                'shippingMethod' => [],
                            ],
                        ],
                    ],
                ];*/

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $orderId));
        $criteria->addAssociations(['lineItems.product', 'deliveries.shippingMethod']);

        return $this->orderRepository->search($criteria, $context);
        // return $client->search('order', $orderAddressFilter)['data'][0];
    }

    public function findDeliveryTimeByMinMax(
        Context $context,
        int $min,
        int $max
    ): IdSearchResult {
        /*        $filterForDeliveryTime = [
                    'filter' => [
                        [
                            'type' => 'contains',
                            'field' => 'unit',
                            'value' => 'day',
                        ],
                        [
                            'type' => 'equals',
                            'field' => 'min',
                            'value' => $min,
                        ],
                        [
                            'type' => 'equals',
                            'field' => 'max',
                            'value' => $max,
                        ],
                    ],
                ];*/

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('min', $min));
        $criteria->addFilter(new EqualsFilter('max', $max));
        $criteria->addFilter(new ContainsFilter('unit', 'day'));

        return $this->deliveryTimeRepository->searchIds($criteria, $context);
        // return $client->searchIds('delivery-time', $filterForDeliveryTime);
    }

    public function findShippingMethodByShippingKey(Context $context): IdSearchResult
    {
        /*        $filterForShippingMethod = [
                    'filter' => [
                        [
                            'type' => 'contains',
                            'field' => 'name',
                            'value' => Defaults::SHIPPING_METHOD_NAME,
                        ],
                    ],
                ];*/

        $criteria = new Criteria();
        $criteria->addFilter(new ContainsFilter('name', Defaults::SHIPPING_METHOD_NAME));

        return $this->shippingMethodRepository->searchIds($criteria, $context);
        // return $client->searchIds('shipping-method', $filterForShippingMethod);
    }

    public function findRuleByName(Context $context, string $name): IdSearchResult
    {
        /*        $filterRule = [
                    'filter' => [
                        [
                            'type' => 'equals',
                            'field' => 'name',
                            'value' => $name,
                        ],
                    ],
                ];*/

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->ruleRepository->searchIds($criteria, $context);
        // return $client->searchIds('rule', $filterRule);
    }

    public function findCustomFieldIdsByName(Context $context, string $name): IdSearchResult
    {
        /*        $customFieldFilter = [
                    'filter' => [
                        [
                            'type' => 'equals',
                            'field' => 'name',
                            'value' => $name,
                        ],
                    ],
                ];*/

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->customFieldRepository->searchIds($criteria, $context);
        // return $client->search('custom-field', $customFieldFilter);
    }

    public function findCustomFieldSetIdsByName(Context $context, string $name): IdSearchResult
    {
        /*        $customFieldFilter = [
                    'filter' => [
                        [
                            'type' => 'equals',
                            'field' => 'name',
                            'value' => $name,
                        ],
                    ],
                ];*/

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->customFieldSetRepository->searchIds($criteria, $context);
        // return $client->search('custom-field-set', $customFieldFilter);
    }
}
