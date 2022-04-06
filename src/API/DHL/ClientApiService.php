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
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $orderId));
        $criteria->addAssociations(['lineItems.product', 'deliveries.shippingMethod']);

        return $this->orderRepository->search($criteria, $context);
    }

    public function findDeliveryTimeByMinMax(
        Context $context,
        int $min,
        int $max
    ): IdSearchResult {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('min', $min));
        $criteria->addFilter(new EqualsFilter('max', $max));
        $criteria->addFilter(new ContainsFilter('unit', 'day'));

        return $this->deliveryTimeRepository->searchIds($criteria, $context);
    }

    public function findShippingMethodByShippingKey(Context $context): IdSearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new ContainsFilter('name', Defaults::SHIPPING_METHOD_NAME));

        return $this->shippingMethodRepository->searchIds($criteria, $context);
    }

    public function findRuleByName(Context $context, string $name): IdSearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->ruleRepository->searchIds($criteria, $context);
    }

    public function findCustomFieldIdsByName(Context $context, string $name): IdSearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->customFieldRepository->searchIds($criteria, $context);
    }

    public function findCustomFieldSetIdsByName(Context $context, string $name): IdSearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->customFieldSetRepository->searchIds($criteria, $context);
    }
}
