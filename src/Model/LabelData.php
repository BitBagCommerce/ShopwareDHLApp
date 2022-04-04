<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Model;

final class LabelData implements LabelDataInterface
{
    private string $labelType;

    private string $shipmentId;

    private string $labelData;

    public function __construct(
        string $labelType,
        string $shipmentId,
        string $labelData
    ) {
        $this->labelType = $labelType;
        $this->shipmentId = $shipmentId;
        $this->labelData = $labelData;
    }

    public function getLabelType(): string
    {
        return $this->labelType;
    }

    public function getShipmentId(): string
    {
        return $this->shipmentId;
    }

    public function getLabelData(): string
    {
        return $this->labelData;
    }
}
