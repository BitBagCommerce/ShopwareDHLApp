<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\Shopware;

use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Criteria;
use Vin\ShopwareSdk\Data\Filter\EqualsFilter;
use Vin\ShopwareSdk\Data\Filter\MultiFilter;
use Vin\ShopwareSdk\Repository\RepositoryInterface;
use Vin\ShopwareSdk\Repository\Struct\EntitySearchResult;
use Vin\ShopwareSdk\Repository\Struct\IdSearchResult;

final class CustomFieldApiService implements CustomFieldApiServiceInterface
{
    private RepositoryInterface $customFieldRepository;

    private RepositoryInterface $customFieldSetRepository;

    public function __construct(RepositoryInterface $customFieldRepository, RepositoryInterface $customFieldSetRepository)
    {
        $this->customFieldRepository = $customFieldRepository;
        $this->customFieldSetRepository = $customFieldSetRepository;
    }

    public function findCustomFieldsByName(array $filters, Context $context): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new MultiFilter('or', $filters));

        return $this->customFieldRepository->search($criteria, $context);
    }

    public function findCustomFieldSetIdsByName(string $name, Context $context): IdSearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->customFieldSetRepository->searchIds($criteria, $context);
    }
}
