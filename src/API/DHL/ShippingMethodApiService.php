<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use BitBag\ShopwareDHLApp\Provider\Defaults;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Criteria;
use Vin\ShopwareSdk\Data\Filter\ContainsFilter;
use Vin\ShopwareSdk\Data\Filter\EqualsFilter;
use Vin\ShopwareSdk\Repository\RepositoryInterface;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

class ShippingMethodApiService implements ShippingMethodApiServiceInterface
{
    private RepositoryInterface $shippingMethodRepository;

    private RepositoryInterface $deliveryTimeRepository;

    private RepositoryInterface $ruleRepository;

    public function __construct(
        RepositoryInterface $shippingMethodRepository,
        RepositoryInterface $deliveryTimeRepository,
        RepositoryInterface $ruleRepository
    ) {
        $this->shippingMethodRepository = $shippingMethodRepository;
        $this->deliveryTimeRepository = $deliveryTimeRepository;
        $this->ruleRepository = $ruleRepository;
    }

    public function findShippingMethodByShippingKey(Context $context): IdSearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new ContainsFilter('name', Defaults::SHIPPING_METHOD_NAME));

        return $this->shippingMethodRepository->searchIds($criteria, $context);
    }

    public function findRuleByName(string $name, Context $context): IdSearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->ruleRepository->searchIds($criteria, $context);
    }

    public function findDeliveryTimeByMinMax(
        int $min,
        int $max,
        Context $context
    ): IdSearchResult {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('min', $min));
        $criteria->addFilter(new EqualsFilter('max', $max));
        $criteria->addFilter(new ContainsFilter('unit', 'day'));

        return $this->deliveryTimeRepository->searchIds($criteria, $context);
    }
}
