<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\Factory\CustomFieldPayloadFactoryInterface;

final class CustomFieldsCreator implements CustomFieldsCreatorInterface
{
    private CustomFieldFilterInterface $detailsPackageFields;

    private CustomFieldPayloadFactoryInterface $createCustomFieldFactory;

    private CustomFieldSetCreatorInterface $customFieldSetCreator;

    public function __construct(
        CustomFieldFilterInterface $detailsPackageFields,
        CustomFieldPayloadFactoryInterface $createCustomFieldFactory,
        CustomFieldSetCreatorInterface $customFieldSetCreator
    ) {
        $this->detailsPackageFields = $detailsPackageFields;
        $this->createCustomFieldFactory = $createCustomFieldFactory;
        $this->customFieldSetCreator = $customFieldSetCreator;
    }

    public function create(ClientInterface $client): void
    {
        $customFieldSet = $this->customFieldSetCreator->create($client);

        $detailsPackageFields = $this->detailsPackageFields->filter($client);

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
