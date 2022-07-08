<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use Alexcherniatin\DHL\Client;
use Alexcherniatin\DHL\Structures\AuthData;

final class DHL24Client
{
    private Client $client;

    private array $authData = [];

    public function __construct(
        string $login,
        string $password,
        bool $sandbox = false
    ) {
        $this->client = new Client($sandbox);

        $this->authData = (new AuthData($login, $password))->structure();
    }

    public function getMyShipmentsCount(string $createdFrom, string $createdTo): object
    {
        $params = [
            'authData' => $this->authData,
            'createdFrom' => $createdFrom,
            'createdTo' => $createdTo,
        ];

        return $this->client->getMyShipmentsCount($params);
    }
}
