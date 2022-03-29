<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\AppSystem\Event;

interface EventInterface
{


    public function getShopId(): string;

    public function getEventData(): array;
}
