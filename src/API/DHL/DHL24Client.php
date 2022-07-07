<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use Alexcherniatin\DHL\Client;
use Alexcherniatin\DHL\Exceptions\SoapException;
use Alexcherniatin\DHL\Structures\AuthData;

final class DHL24Client
{
    /** @var Client */
    private $client;

    /** @var array */
    private $authData = [];

    public function __construct(
        string $login,
        string $password,
        bool $sandbox = false
    )
    {
        $this->client = new Client($sandbox);

        if (!$this->client) {
            throw new SoapException('Connection error');
        }

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
