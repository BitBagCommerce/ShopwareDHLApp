<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Model;

interface LabelDataInterface
{
    public function getLabelType(): string;

    public function getShipmentId(): string;

    public function getLabelData(): string;
}
