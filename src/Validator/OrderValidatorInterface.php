<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Validator;

use Vin\ShopwareSdk\Data\Entity\Order\OrderEntity;

interface OrderValidatorInterface
{
    public function validateOrder(OrderEntity $order): void;

    public function validateCustomFields(?array $customFields): void;
}
