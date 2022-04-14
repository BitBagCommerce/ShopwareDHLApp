<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\Shopware;

use BitBag\ShopwareDHLApp\Factory\CustomFieldPayloadFactoryInterface;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Repository\RepositoryInterface;

final class CustomFieldsCreator implements CustomFieldsCreatorInterface
{
    private CustomFieldFilterInterface $detailsPackageFields;

    private CustomFieldPayloadFactoryInterface $createCustomFieldFactory;

    private CustomFieldSetCreatorInterface $customFieldSetCreator;

    private RepositoryInterface $customFieldRepository;

    public function __construct(
        CustomFieldFilterInterface $detailsPackageFields,
        CustomFieldPayloadFactoryInterface $createCustomFieldFactory,
        CustomFieldSetCreatorInterface $customFieldSetCreator,
        RepositoryInterface $customFieldRepository
    ) {
        $this->detailsPackageFields = $detailsPackageFields;
        $this->createCustomFieldFactory = $createCustomFieldFactory;
        $this->customFieldSetCreator = $customFieldSetCreator;
        $this->customFieldRepository = $customFieldRepository;
    }

    public function create(Context $context): void
    {
        $customFieldSet = $this->customFieldSetCreator->create($context);

        $detailsPackageFields = $this->detailsPackageFields->filter($context);

        foreach ($detailsPackageFields as $detailsPackageField) {
            $customFieldArr = $this->createCustomFieldFactory->create(
                $detailsPackageField['customFieldName'],
                $detailsPackageField['type'],
                $detailsPackageField['key'],
                $detailsPackageField['label'],
                $customFieldSet['customFieldSetId'],
                $customFieldSet['customFieldSet']
            );

            $this->customFieldRepository->create($customFieldArr, $context);
            // $client->createEntity('custom-field', $customFieldArr);
        }
    }
}
