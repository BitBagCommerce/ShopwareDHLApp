<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Validator;

use BitBag\ShopwareDHLApp\Exception\OrderException;
use BitBag\ShopwareDHLApp\Exception\PackageDetailsException;
use BitBag\ShopwareDHLApp\Provider\Defaults;
use Vin\ShopwareSdk\Data\Entity\Order\OrderEntity;

final class OrderValidator implements OrderValidatorInterface
{
    public function validate(OrderEntity $order): void
    {
        if (null === $order->deliveries?->first()->shippingOrderAddress) {
            throw new OrderException('bitbag.shopware_dhl_app.order.empty_order_address');
        }

        if (null === $order->deliveries?->first()?->shippingOrderAddress->phoneNumber) {
            throw new OrderException('bitbag.shopware_dhl_app.order.empty_phone_number');
        }

        if ('DHL' !== $order->deliveries?->first()?->shippingMethod?->name) {
            throw new OrderException('bitbag.shopware_dhl_app.order.not_for_dhl');
        }

        if (!preg_match('/[0-9][0-9][-][0-9][0-9][0-9]/', $order->deliveries?->first()->shippingOrderAddress?->zipcode)) {
            throw new OrderException('bitbag.shopware_dhl_app.order.invalid_zipcode');
        }

        $customFields = $order->getCustomFields();

        if (null === $customFields) {
            throw new PackageDetailsException('bitbag.shopware_dhl_app.order.empty_package_details');
        }

        if (null === $customFields[Defaults::PACKAGE_COUNTRY_CODE]) {
            throw new PackageDetailsException('bitbag.shopware_dhl_app.order.empty_country_code');
        }

        if (
            0 === $customFields[Defaults::PACKAGE_DEPTH] ||
            0 === $customFields[Defaults::PACKAGE_HEIGHT] ||
            0 === $customFields[Defaults::PACKAGE_WIDTH]
        ) {
            throw new PackageDetailsException('bitbag.shopware_dhl_app.order.empty_package_dimensions');
        }
    }
}
