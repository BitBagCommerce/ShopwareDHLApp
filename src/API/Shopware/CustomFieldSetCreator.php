<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\Shopware;

use BitBag\ShopwareAppSkeleton\API\DHL\ClientApiServiceInterface;
use BitBag\ShopwareAppSkeleton\Factory\CustomFieldSetPayloadFactoryInterface;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

class CustomFieldSetCreator implements CustomFieldSetCreatorInterface
{
    private ClientApiServiceInterface $apiService;

    private CustomFieldSetPayloadFactoryInterface $customFieldSetFactory;

    private RepositoryInterface $customFieldSetRepository;

    public function __construct(
        ClientApiServiceInterface $apiService,
        CustomFieldSetPayloadFactoryInterface $customFieldSetFactory,
        RepositoryInterface $customFieldSetRepository
    ) {
        $this->apiService = $apiService;
        $this->customFieldSetFactory = $customFieldSetFactory;
        $this->customFieldSetRepository = $customFieldSetRepository;
    }

    public function create(Context $context): array
    {
        $customFieldSet = $this->apiService->findCustomFieldSetIdsByName($context, Defaults::CUSTOM_FIELDS_PREFIX);

        if (0 === $customFieldSet->getTotal()) {
            $customFieldSet = $this->customFieldSetFactory->create(
                Defaults::CUSTOM_FIELDS_PREFIX,
                'Package details',
                'order'
            );

            $this->customFieldSetRepository->create($customFieldSet, $context);
            // $context->createEntity('custom-field-set', $customFieldSet);
            $customFieldSet = $this->apiService->findCustomFieldSetIdsByName($context, Defaults::CUSTOM_FIELDS_PREFIX);
        }

        return [
            'customFieldSetId' => $customFieldSet->firstId(),
            'customFieldSet' => $customFieldSet,
        ];
    }
}
