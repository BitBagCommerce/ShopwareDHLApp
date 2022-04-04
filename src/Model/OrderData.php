<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Model;

final class OrderData implements OrderDataInterface
{
    private array $shippingAddress;

    private string $customerEmail;

    private int $totalWeight;

    private array $customFields;

    private string $shopId;

    private string $orderId;

    public function __construct(
        array $shippingAddress,
        string $customerEmail,
        int $totalWeight,
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

    public function getShippingAddress(): array
    {
        return $this->shippingAddress;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getTotalWeight(): int
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
