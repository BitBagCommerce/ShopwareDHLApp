<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\Shopware;

use BitBag\ShopwareDHLApp\Provider\CustomFieldFilterDataProviderInterface;
use BitBag\ShopwareDHLApp\Provider\CustomFieldNamesProviderInterface;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Entity\CustomField\CustomFieldEntity;

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
        $customFieldNames = $this->customFieldNamesProvider->getFields();

        $customFieldFilters = $this->customFieldFilterDataProvider->getCustomFieldFilter($customFieldNames);

        $customFields = $this->customFieldApiService->findCustomFieldsByName($customFieldFilters, $context);

        $existingCustomFields = [];

        /** @var CustomFieldEntity $customField */
        foreach ($customFields->getEntities() as $customField) {
            $existingCustomFields[] = $customField->name;
        }

        $detailsPackageFields = [];

        foreach ($customFieldNames as $key => $item) {
            $type = $item['type'];

            $customFieldName = Defaults::CUSTOM_FIELDS_PREFIX . '_' . $item['name'];

            if (in_array($customFieldName, $existingCustomFields)) {
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
