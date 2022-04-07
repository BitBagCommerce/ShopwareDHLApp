<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Provider;

final class Defaults
{
    public const SHIPPING_METHOD_NAME = 'DHL';

    public const CUSTOM_FIELDS_PREFIX = 'bitbag.shopware_dhl_app.package_details';

    public const AVAILABILITY_RULE = 'Always valid (Default)';

    public const PACKAGE_HEIGHT = 'bitbag.shopware_dhl_app.package_details_depth';

    public const PACKAGE_DEPTH = 'bitbag.shopware_dhl_app.package_details_depth';

    public const PACKAGE_WIDTH = 'bitbag.shopware_dhl_app.package_details_width';

    public const PACKAGE_SHIPPING_DATE = 'bitbag.shopware_dhl_app.package_details_shippingDate';

    public const PACKAGE_DESCRIPTION = 'bitbag.shopware_dhl_app.package_details_description';

    public const PACKAGE_INSURANCE = 'bitbag.shopware_dhl_app.package_insurance';

    public const PACKAGE_COUNTRY_CODE = 'bitbag.shopware_dhl_app.package_details_countryCode';
}
