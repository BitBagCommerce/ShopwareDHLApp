<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Validator;

use Vin\ShopwareSdk\Data\Entity\Order\OrderEntity;

interface OrderValidatorInterface
{
    public function validate(OrderEntity $order, ?array $customFields): void;
}
