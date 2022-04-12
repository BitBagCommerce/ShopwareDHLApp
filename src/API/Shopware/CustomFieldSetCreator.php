<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\Shopware;

use BitBag\ShopwareDHLApp\Factory\CustomFieldSetPayloadFactoryInterface;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

class CustomFieldSetCreator implements CustomFieldSetCreatorInterface
{
    private CustomFieldApiServiceInterface $customFieldApiService;

    private CustomFieldSetPayloadFactoryInterface $customFieldSetFactory;

    private RepositoryInterface $customFieldSetRepository;

    public function __construct(
        CustomFieldApiServiceInterface $customFieldApiService,
        CustomFieldSetPayloadFactoryInterface $customFieldSetFactory,
        RepositoryInterface $customFieldSetRepository
    ) {
        $this->customFieldApiService = $customFieldApiService;
        $this->customFieldSetFactory = $customFieldSetFactory;
        $this->customFieldSetRepository = $customFieldSetRepository;
    }

    public function create(Context $context): array
    {
        $customFieldSet = $this->customFieldApiService->findCustomFieldSetIdsByName(Defaults::CUSTOM_FIELDS_PREFIX, $context);

        if (0 === $customFieldSet->getTotal()) {
            $customFieldSet = $this->customFieldSetFactory->create(
                Defaults::CUSTOM_FIELDS_PREFIX,
                'Package details',
                'order'
            );

            $this->customFieldSetRepository->create($customFieldSet, $context);
            $customFieldSet = $this->customFieldApiService->findCustomFieldSetIdsByName(Defaults::CUSTOM_FIELDS_PREFIX, $context);
        }

        return [
            'customFieldSetId' => $customFieldSet->firstId(),
            'customFieldSet' => $customFieldSet,
        ];
    }
}
