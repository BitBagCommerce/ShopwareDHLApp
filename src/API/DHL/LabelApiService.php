<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use Alexcherniatin\DHL\Structures\ItemToPrint;
use BitBag\ShopwareAppSkeleton\Exception\LabelNotFoundException;
use BitBag\ShopwareAppSkeleton\Model\LabelData;
use BitBag\ShopwareAppSkeleton\Model\LabelDataInterface;

final class LabelApiService implements LabelApiServiceInterface
{
    private ApiResolverInterface $apiResolver;

    public function __construct(ApiResolverInterface $apiResolver)
    {
        $this->apiResolver = $apiResolver;
    }

    public function fetchLabel(string $parcelId, string $shopId): LabelDataInterface
    {
        $dhl = $this->apiResolver->getApi($shopId);

        $itemsToPrint = [];

        $itemsToPrint[] = (new ItemToPrint())
            ->setLabelType(ItemToPrint::LABEL_TYPE_LBLP)
            ->setShipmentId($parcelId)
            ->structure();

        try {
            $result = $dhl->getLabels($itemsToPrint);
        } catch (LabelNotFoundException $e) {
            throw new LabelNotFoundException($e->getMessage());
        }

        return new LabelData($result['labelType'], $result['shipmentId'], $result['labelData']);
    }
}
