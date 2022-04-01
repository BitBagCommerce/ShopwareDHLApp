<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use Alexcherniatin\DHL\Exceptions\DHL24Exception;
use Alexcherniatin\DHL\Exceptions\SoapException;
use Alexcherniatin\DHL\Structures\ItemToPrint;
use BitBag\ShopwareAppSkeleton\Model\LabelData;
use BitBag\ShopwareAppSkeleton\Model\LabelDataInterface;

final class LabelFetcher implements LabelFetcherInterface
{
    private ApiResolverInterface $apiResolver;

    public function __construct(ApiResolverInterface $apiResolver)
    {
        $this->apiResolver = $apiResolver;
    }

    public function fetchLabel(string $shopId, string $parcelId): LabelDataInterface
    {
        $dhl = $this->apiResolver->getApi($shopId);

        $itemsToPrint = [];

        $itemsToPrint[] = (new ItemToPrint())
            ->setLabelType(ItemToPrint::LABEL_TYPE_LBLP)
            ->setShipmentId($parcelId)
            ->structure();

        try {
            $result = $dhl->getLabels($itemsToPrint);
        } catch (DHL24Exception|SoapException $e) {
            throw new DHL24Exception($e->getMessage());
        }

        return new LabelData($result['labelType'], $result['shipmentId'], $result['labelData']);
    }
}
