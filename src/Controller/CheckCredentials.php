<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Controller;

use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Error;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Success;
use BitBag\ShopwareAppSystemBundle\Response\FeedbackResponse;
use BitBag\ShopwareDHLApp\API\DHL\DHL24Client;
use SoapFault;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CheckCredentials
{
    public const CREATED_FROM = '2022-07-05';

    public const CREATED_TO = '2022-07-06';

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function __invoke(Request $request): FeedbackResponse
    {
        $data = $request->toArray();

        try {
            $dhl = new DHL24Client($data['username'], $data['password'], true);
            $dhl->getMyShipmentsCount(self::CREATED_FROM, self::CREATED_TO);
        } catch (SoapFault $e) {
            return new FeedbackResponse(new Error($this->translator->trans($e->getMessage(), [], 'api')));
        }

        return new FeedbackResponse(new Success($this->translator->trans('bitbag.shopware_dhl_app.config.connected')));
    }
}
