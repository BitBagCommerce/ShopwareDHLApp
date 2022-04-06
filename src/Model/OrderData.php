<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Model;

use Vin\ShopwareSdk\Data\Entity\OrderAddress\OrderAddressEntity;

final class OrderData implements OrderDataInterface
{
    private OrderAddressEntity $shippingAddress;

    private string $customerEmail;

    private float $totalWeight;

    private array $customFields;

    private string $shopId;

    private string $orderId;

    public function __construct(
        OrderAddressEntity $shippingAddress,
        string $customerEmail,
        float $totalWeight,
        array $customFields,
        string $shopId,
        string $orderId
    ) {
        $this->shippingAddress = $shippingAddress;
        $this->customerEmail = $customerEmail;
        $this->totalWeight = $totalWeight;
        $this->customFields = $customFields;
        $this->shopId = $shopId;
        $this->orderId = $orderId;
    }

    public function getShippingAddress(): OrderAddressEntity
    {
        return $this->shippingAddress;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getTotalWeight(): float
    {
        return $this->totalWeight;
    }

    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    public function getShopId(): string
    {
        return $this->shopId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }
}
