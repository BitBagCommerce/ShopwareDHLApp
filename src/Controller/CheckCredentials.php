<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Controller;

use Alexcherniatin\DHL\DHL24;
use Alexcherniatin\DHL\Exceptions\SoapException;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Error;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Success;
use BitBag\ShopwareAppSystemBundle\Response\FeedbackResponse;
use Symfony\Component\HttpFoundation\Request;

final class CheckCredentials
{
    public function __invoke(Request $request): FeedbackResponse
    {
        $data = json_decode($request->getContent());

        try {
            $dhl = new DHL24($data->username, $data->password, $data->accountNumber, true);
        } catch (SoapException $e) {
            return new FeedbackResponse(new Error($e->getMessage()));
        }

        return new FeedbackResponse(new Success('Ok'));
    }
}
