<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\Factory\CustomFieldPayloadFactoryInterface;

final class CustomFieldsCreator implements CustomFieldsCreatorInterface
{
    private DetailsPackageFieldsServiceInterface $detailsPackageFieldsFactory;

    private CustomFieldPayloadFactoryInterface $createCustomFieldFactory;

    private CustomFieldSetCreatorInterface $customFieldSetCreator;

    public function __construct(
        DetailsPackageFieldsServiceInterface $detailsPackageFieldsFactory,
        CustomFieldPayloadFactoryInterface $createCustomFieldFactory,
        CustomFieldSetCreatorInterface $customFieldSetCreator
    ) {
        $this->detailsPackageFieldsFactory = $detailsPackageFieldsFactory;
        $this->createCustomFieldFactory = $createCustomFieldFactory;
        $this->customFieldSetCreator = $customFieldSetCreator;
    }

    public function create(ClientInterface $client): void
    {
        $customFieldSet = $this->customFieldSetCreator->create($client);

        $detailsPackageFields = $this->detailsPackageFieldsFactory->create($client);

        foreach ($detailsPackageFields as $detailsPackageField) {
            $customFieldArr = $this->createCustomFieldFactory->create(
                $detailsPackageField['customFieldName'],
                $detailsPackageField['type'],
                $detailsPackageField['key'],
                $detailsPackageField['label'],
                $customFieldSet['customFieldSetId'],
                $customFieldSet['customFieldSet']
            );

            $client->createEntity('custom-field', $customFieldArr);
        }
    }
}
