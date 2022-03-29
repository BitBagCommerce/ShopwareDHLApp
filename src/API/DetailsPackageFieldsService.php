<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API;

use BitBag\ShopwareAppSkeleton\AppSystem\Client\ClientInterface;
use BitBag\ShopwareAppSkeleton\Provider\CustomFieldNamesProviderInterface;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

final class DetailsPackageFieldsService implements DetailsPackageFieldsServiceInterface
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

    /**
     * @return ((int|string)|mixed)[][]
     *
     * @psalm-return list<array{customFieldName: string, type: mixed, key: array-key, label: mixed}>
     */
    public function create(ClientInterface $client): array
    {
        $customFieldNames = $this->customFieldNamesProvider->getFields();

        $detailsPackageFields = [];

        foreach ($customFieldNames as $key => $item) {
            $type = $item['type'];

            $customFieldName = Defaults::CUSTOM_FIELDS_PREFIX . '_' . $item['name'];

            $customField = $this->clientApiService->findCustomFieldIdsByName($client, $customFieldName);

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
