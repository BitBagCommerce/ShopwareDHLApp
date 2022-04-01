<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Model;

interface LabelDataInterface
{
    public function getLabelType(): string;

    public function getShipmentId(): string;

    public function getLabelData(): string;
}
