<?php

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\Factory\CustomFieldSetPayloadFactoryInterface;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

class CustomFieldSetCreator implements CustomFieldSetCreatorInterface
{
    private ClientApiServiceInterface $apiService;

    private CustomFieldSetPayloadFactoryInterface $customFieldSetFactory;

    public function __construct(
        ClientApiServiceInterface $apiService,
        CustomFieldSetPayloadFactoryInterface $customFieldSetFactory
    ) {
        $this->apiService = $apiService;
        $this->customFieldSetFactory = $customFieldSetFactory;
    }

    public function create(ClientInterface $client): array
    {
        $customFieldSet = $this->apiService->findCustomFieldSetByName($client, Defaults::CUSTOM_FIELDS_PREFIX);

        if (0 === $customFieldSet['total']) {
            $customFieldSet = $this->customFieldSetFactory->create(
                Defaults::CUSTOM_FIELDS_PREFIX,
                'Package details',
                'order'
            );
            $client->createEntity('custom-field-set', $customFieldSet);
            $customFieldSet = $this->apiService->findCustomFieldSetByName($client, Defaults::CUSTOM_FIELDS_PREFIX);
        }

        $customFieldSetId = $customFieldSet['data'][0]['id'];

        return [
            'customFieldSetId' => $customFieldSetId,
            'customFieldSet' => $customFieldSet,
        ];
    }
}
