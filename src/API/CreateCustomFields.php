<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\Factory\CustomFieldPayloadFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\CustomFieldSetPayloadFactoryInterface;
use BitBag\ShopwareAppSkeleton\Factory\CreateDetailsPackageFieldsFactoryInterface;

final class CreateCustomFields implements CreateCustomFieldsInterface
{
    public const CUSTOM_FIELD_PREFIX = 'package_details';

    private CreateDetailsPackageFieldsFactoryInterface $createDetailsPackageFieldsFactory;

    private CustomFieldPayloadFactoryInterface $createCustomFieldFactory;

    private ClientApiServiceInterface $apiService;

    private CustomFieldSetPayloadFactoryInterface $createCustomFieldSetFactory;

    public function __construct(
        CreateDetailsPackageFieldsFactoryInterface $createDetailsPackageFieldsFactory,
        CustomFieldPayloadFactoryInterface         $createCustomFieldFactory,
        ClientApiServiceInterface                  $apiService,
        CustomFieldSetPayloadFactoryInterface $createCustomFieldSetFactory
    ) {
        $this->createDetailsPackageFieldsFactory = $createDetailsPackageFieldsFactory;
        $this->createCustomFieldFactory = $createCustomFieldFactory;
        $this->apiService = $apiService;
        $this->createCustomFieldSetFactory = $createCustomFieldSetFactory;
    }

    public function create(ClientInterface $client)
    {
        $customFieldSet = $this->apiService->findCustomFieldSetByName($client, self::CUSTOM_FIELD_PREFIX, );

        if (0 === $customFieldSet['total']) {
            $customFieldSet = $this->createCustomFieldSetFactory->create(
                self::CUSTOM_FIELD_PREFIX,
                'Package details',
                'order'
            );
            $client->createEntity('custom-field-set', $customFieldSet);
            $customFieldSet = $this->apiService->findCustomFieldSetByName($client, self::CUSTOM_FIELD_PREFIX);
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
