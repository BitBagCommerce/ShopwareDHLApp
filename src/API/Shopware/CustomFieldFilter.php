<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\Shopware;

use BitBag\ShopwareAppSkeleton\API\DHL\ClientApiService;
use BitBag\ShopwareAppSkeleton\Provider\CustomFieldNamesProviderInterface;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;
use Vin\ShopwareSdk\Data\Context;

final class CustomFieldFilter implements CustomFieldFilterInterface
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

    public function filter(Context $context): array
    {
        $customFieldNames = $this->customFieldNamesProvider->getFields();

        $detailsPackageFields = [];

        foreach ($customFieldNames as $key => $item) {
            $type = $item['type'];

            $customFieldName = Defaults::CUSTOM_FIELDS_PREFIX.'_'.$item['name'];

            $customField = $this->clientApiService->findCustomFieldIdsByName($context, $customFieldName);

            if (0 !== $customField->getTotal()) {
                continue;
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
