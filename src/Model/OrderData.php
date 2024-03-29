<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Model;

use Vin\ShopwareSdk\Data\Entity\OrderAddress\OrderAddressEntity;

final class OrderData implements OrderDataInterface
{
    private OrderAddressEntity $shippingAddress;

    private string $customerEmail;

    private float $totalWeight;

    private array $customFields;

    private string $shopId;

    private string $orderId;

    private array $street;

    private string $salesChannelId;

    public function __construct(
        OrderAddressEntity $shippingAddress,
        string $salesChannelId,
        string $customerEmail,
        float $totalWeight,
        array $customFields,
        string $shopId,
        string $orderId,
        array $street
    ) {
        $this->shippingAddress = $shippingAddress;
        $this->salesChannelId = $salesChannelId;
        $this->customerEmail = $customerEmail;
        $this->totalWeight = $totalWeight;
        $this->customFields = $customFields;
        $this->shopId = $shopId;
        $this->orderId = $orderId;
        $this->street = $street;
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

    public function getStreet(): array
    {
        return $this->street;
    }

    public function getSalesChannelId(): string
    {
        return $this->salesChannelId;
    }

    public function setSalesChannelId(string $salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }
}
