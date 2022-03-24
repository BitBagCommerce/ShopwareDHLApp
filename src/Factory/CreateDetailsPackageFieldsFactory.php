<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use BitBag\ShopwareAppSkeleton\API\ClientApiService;
use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\Provider\CustomFieldNamesProviderInterface;

final class CreateDetailsPackageFieldsFactory implements CreateDetailsPackageFieldsFactoryInterface
{
    private CustomFieldNamesProviderInterface $customFieldNamesProvider;

    private ClientApiService $clientApiService;

    public function __construct(
        CustomFieldNamesProviderInterface $customFieldNamesProvider,
        ClientApiService $clientApiService
    ) {
        $this->customFieldNamesProvider = $customFieldNamesProvider;
        $this->clientApiService = $clientApiService;
    }

    public function create(ClientInterface $client): array
    {
        $customFieldPrefix = 'package_details';

        $customFieldNames = $this->customFieldNamesProvider->getFields();

        $detailsPackageFields = [];

        foreach ($customFieldNames as $key => $item) {
            $type = $item['type'];

            $customFieldName = $customFieldPrefix.'_'.$item['name'];

            $customField = $this->clientApiService->findIdsCustomFieldByName($customFieldName, $client);

            if (0 !== $customField['total']) {
                return [];
            }

            $detailsPackageFields[] = [
                'customFieldName' => $customFieldName,
                'type' => $type,
                'key' => $key,
                'label' => $item['label'],
            ];
        }

        return $detailsPackageFields;
    }
}
