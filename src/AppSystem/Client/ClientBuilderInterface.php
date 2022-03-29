<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\AppSystem\Client;

interface ClientBuilderInterface
{


    public function withHeader(array $header): self;

    public function buildClient(): ClientInterface;
}
