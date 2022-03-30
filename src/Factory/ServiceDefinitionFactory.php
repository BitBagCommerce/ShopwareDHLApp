<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Factory;

use Alexcherniatin\DHL\Exceptions\InvalidStructureException;
use Alexcherniatin\DHL\Structures\ServiceDefinition;
use BitBag\ShopwareAppSkeleton\Provider\Defaults;

final class ServiceDefinitionFactory implements ServiceDefinitionFactoryInterface
{
    /**
     * @throws InvalidStructureException
     */
    public function create(array $customFields): array
    {
        if (isset($customFields[Defaults::PACKAGE_INSURANCE])) {
            return (new ServiceDefinition())
                ->setProduct(ServiceDefinition::PRODUCT_DOMESTIC_SHIPMENT)
                ->setInsurance(true)
                ->setInsuranceValue($customFields[Defaults::PACKAGE_INSURANCE])
                ->structure();
        }

        return (new ServiceDefinition())
                ->setProduct(ServiceDefinition::PRODUCT_DOMESTIC_SHIPMENT)
                ->setInsurance(false)
                ->setInsuranceValue(0)
                ->structure();
    }
}
