<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\AppSystem\Credentials;

interface OAuthCredentialsInterface
{


    public function getAccessToken(): string;

    public function isExpired(): bool;
}
