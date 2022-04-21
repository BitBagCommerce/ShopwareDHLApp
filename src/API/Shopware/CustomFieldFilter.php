<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\Shopware;

use BitBag\ShopwareDHLApp\Provider\CustomFieldFilterDataProviderInterface;
use BitBag\ShopwareDHLApp\Provider\CustomFieldNamesProviderInterface;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use Vin\ShopwareSdk\Data\Context;

final class CustomFieldFilter implements CustomFieldFilterInterface
{
    private CustomFieldNamesProviderInterface $customFieldNamesProvider;

    private CustomFieldApiServiceInterface $customFieldApiService;

    private CustomFieldFilterDataProviderInterface $customFieldFilterDataProvider;

    public function __construct(
        CustomFieldNamesProviderInterface $customFieldNamesProvider,
        CustomFieldApiServiceInterface $customFieldApiService,
        CustomFieldFilterDataProviderInterface $customFieldFilterDataProvider
    ) {
        $this->customFieldNamesProvider = $customFieldNamesProvider;
        $this->customFieldApiService = $customFieldApiService;
        $this->customFieldFilterDataProvider = $customFieldFilterDataProvider;
    }

    public function filter(Context $context): array
    {
        $customFieldNamesArray = $this->customFieldNamesProvider->getFields();

        $customFieldsFilters = $this->customFieldFilterDataProvider->getCustomFieldsFilter($customFieldNamesArray);

        $customFields = $this->customFieldApiService->findCustomFieldsByName($customFieldsFilters, $context);

        $customFieldsThatAlreadyExists = [];

        foreach ($customFields->getEntities() as $customField) {
            $customField = (array) $customField;

            $customFieldsThatAlreadyExists[] = $customField['name'];
        }

        $detailsPackageFields = [];

        foreach ($customFieldNamesArray as $key => $item) {
            $type = $item['type'];

            $customFieldName = Defaults::CUSTOM_FIELDS_PREFIX . '_' . $item['name'];

            if (in_array($customFieldName, $customFieldsThatAlreadyExists)) {
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
