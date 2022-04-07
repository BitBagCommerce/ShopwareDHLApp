<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\Shopware;

use BitBag\ShopwareDHLApp\Provider\CustomFieldNamesProviderInterface;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use Vin\ShopwareSdk\Data\Context;

final class CustomFieldFilter implements CustomFieldFilterInterface
{
    private CustomFieldNamesProviderInterface $customFieldNamesProvider;

    private CustomFieldApiServiceInterface $customFieldApiService;

    public function __construct(
        CustomFieldNamesProviderInterface $customFieldNamesProvider,
        CustomFieldApiServiceInterface $customFieldApiService
    ) {
        $this->customFieldNamesProvider = $customFieldNamesProvider;
        $this->customFieldApiService = $customFieldApiService;
    }

    public function filter(Context $context): array
    {
        $customFieldNames = $this->customFieldNamesProvider->getFields();

        $detailsPackageFields = [];

        foreach ($customFieldNames as $key => $item) {
            $type = $item['type'];

            $customFieldName = Defaults::CUSTOM_FIELDS_PREFIX . '_' . $item['name'];

            $customField = $this->customFieldApiService->findCustomFieldIdsByName($customFieldName, $context);

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
