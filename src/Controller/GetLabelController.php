<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Controller;

use BitBag\ShopwareAppSystemBundle\Model\Action\ActionInterface;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\NewTab;
use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Error;
use BitBag\ShopwareAppSystemBundle\Response\FeedbackResponse;
use BitBag\ShopwareDHLApp\Repository\LabelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class GetLabelController extends AbstractController
{
    private LabelRepository $labelRepository;

    private TranslatorInterface $translator;

    public function __construct(
        LabelRepository $labelRepository,
        TranslatorInterface $translator,
    ) {
        $this->labelRepository = $labelRepository;
        $this->translator = $translator;
    }

    public function __invoke(Request $request, ActionInterface $action): Response
    {
        $data = $request->toArray();

        $orderId = $data['data']['ids'][0];

        $shopId = $action->getSource()->getShopId();

        $label = $this->labelRepository->findByOrderId($orderId, $shopId);

        if (null === $label) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.not_found')));
        }

        $redirectUrl = $this->generateUrl(
            'show_label',
            ['orderId' => $orderId],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new FeedbackResponse(new NewTab($redirectUrl));
    }
}
