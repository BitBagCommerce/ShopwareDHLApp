<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use Alexcherniatin\DHL\Structures\ServiceDefinition;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

final class ServiceDefinitionFactory implements ServiceDefinitionFactoryInterface
{
    public function create(array $customFields): array
    {
        $insurance = false;
        $insuranceValue = 0;

        if (isset($customFields[Defaults::PACKAGE_INSURANCE])) {
            $insurance = true;
            $insuranceValue = $customFields[Defaults::PACKAGE_INSURANCE];
        }

        return (new ServiceDefinition())
                ->setProduct(ServiceDefinition::PRODUCT_DOMESTIC_SHIPMENT)
                ->setInsurance($insurance)
                ->setInsuranceValue($insuranceValue)
                ->structure();
    }
}
