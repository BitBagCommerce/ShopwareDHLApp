<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\Factory\CustomFieldPayloadFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\CustomFieldSetPayloadFactoryInterface;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

final class CustomFieldsCreator implements CustomFieldsCreatorInterface
{
    private DetailsPackageFieldsServiceInterface $createDetailsPackageFieldsFactory;

    private CustomFieldPayloadFactoryInterface $createCustomFieldFactory;

    private ClientApiServiceInterface $apiService;

    private CustomFieldSetPayloadFactoryInterface $createCustomFieldSetFactory;

    public function __construct(
        DetailsPackageFieldsServiceInterface $createDetailsPackageFieldsFactory,
        CustomFieldPayloadFactoryInterface $createCustomFieldFactory,
        ClientApiServiceInterface $apiService,
        CustomFieldSetPayloadFactoryInterface $createCustomFieldSetFactory
    ) {
        $this->createDetailsPackageFieldsFactory = $createDetailsPackageFieldsFactory;
        $this->createCustomFieldFactory = $createCustomFieldFactory;
        $this->apiService = $apiService;
        $this->createCustomFieldSetFactory = $createCustomFieldSetFactory;
    }

    public function create(ClientInterface $client)
    {
        $customFieldSet = $this->apiService->findCustomFieldSetByName($client, Defaults::CUSTOM_FIELDS_PREFIX);

        if (0 === $customFieldSet['total']) {
            $customFieldSet = $this->createCustomFieldSetFactory->create(
                Defaults::CUSTOM_FIELDS_PREFIX,
                'Package details',
                'order'
            );
            $client->createEntity('custom-field-set', $customFieldSet);
            $customFieldSet = $this->apiService->findCustomFieldSetByName($client, Defaults::CUSTOM_FIELDS_PREFIX);
        }
        $customFieldSetId = $customFieldSet['data'][0]['id'];

        $detailsPackageFields = $this->createDetailsPackageFieldsFactory->create($client);

        foreach ($detailsPackageFields as $detailsPackageField) {
            $customFieldArr = $this->createCustomFieldFactory->create(
                $detailsPackageField['customFieldName'],
                $detailsPackageField['type'],
                $detailsPackageField['key'],
                $detailsPackageField['label'],
                $customFieldSetId,
                $customFieldSet
            );

            $client->createEntity('custom-field', $customFieldArr);
        }
    }
}
