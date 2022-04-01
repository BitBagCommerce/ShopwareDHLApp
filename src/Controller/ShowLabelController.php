<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\API\DHL\LabelFetcherInterface;
use BitBag\ShopwareAppSkeleton\Exception\LabelNotFoundException;
use BitBag\ShopwareAppSkeleton\Repository\LabelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowLabelController
{
    private LabelRepository $labelRepository;

    private LabelFetcherInterface $labelFetcher;

    public function __construct(
        LabelRepository $labelRepository,
        LabelFetcherInterface $labelFetcher
    ) {
        $this->labelRepository = $labelRepository;
        $this->labelFetcher = $labelFetcher;
    }

    public function __invoke(Request $request): Response
    {
        $orderId = $request->query->get('orderId');
        $shopId = $request->query->get('shop-id');

        if (null === $orderId || null == $shopId) {
            throw new LabelNotFoundException('bitbag.shopware_dhl_app.order.not_found');
        }

        $label = $this->labelRepository->findByOrderId($orderId, $shopId);

        if (null === $label) {
            throw new LabelNotFoundException('bitbag.shopware_dhl_app.order.not_found');
        }

        $labelResponse = $this->labelFetcher->fetchLabel($shopId, $label->getParcelId());

        $filename = sprintf('filename="order_%s.pdf"', $label->getOrderId());

        $response = new Response(base64_decode($labelResponse->getLabelData()));
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Disposition', $filename);

        return $response;
    }
}
