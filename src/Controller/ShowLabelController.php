<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Controller;

use BitBag\ShopwareAppSystemBundle\Model\Feedback\Notification\Error;
use BitBag\ShopwareAppSystemBundle\Response\FeedbackResponse;
use BitBag\ShopwareDHLApp\API\DHL\LabelApiServiceInterface;
use BitBag\ShopwareDHLApp\Entity\LabelInterface;
use BitBag\ShopwareDHLApp\Repository\LabelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ShowLabelController
{
    private LabelRepository $labelRepository;

    private LabelApiServiceInterface $labelApiService;

    private TranslatorInterface $translator;

    public function __construct(
        LabelRepository $labelRepository,
        LabelApiServiceInterface $labelApiService,
        TranslatorInterface $translator,
        ) {
        $this->labelRepository = $labelRepository;
        $this->labelApiService = $labelApiService;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): Response
    {
        $orderId = $request->query->get('orderId');
        $shopId = $request->query->get('shop-id');

        if (null === $orderId || null == $shopId) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.not_found')));
        }

        $label = $this->labelRepository->findByOrderId($orderId, $shopId);

        if (null === $label) {
            return new FeedbackResponse(new Error($this->translator->trans('bitbag.shopware_dhl_app.order.not_found')));
        }

        return $this->getLabelResponse($shopId, $label);
    }

    private function getLabelResponse(string $shopId, LabelInterface $label): Response
    {
        $labelResponse = $this->labelApiService->fetchLabel($label->getParcelId(), $shopId);

        $filename = sprintf('filename="order_%s.pdf"', $label->getOrderId());

        $response = new Response(base64_decode($labelResponse->getLabelData()));
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Disposition', $filename);

        return $response;
    }
}
