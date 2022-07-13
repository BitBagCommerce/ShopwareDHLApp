<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\API\DHL;

use Alexcherniatin\DHL\Structures\ItemToPrint;
use BitBag\ShopwareDHLApp\Exception\LabelNotFoundException;
use BitBag\ShopwareDHLApp\Model\LabelData;
use BitBag\ShopwareDHLApp\Model\LabelDataInterface;

final class LabelApiService implements LabelApiServiceInterface
{
    private ApiResolverInterface $apiResolver;

    public function __construct(ApiResolverInterface $apiResolver)
    {
        $this->apiResolver = $apiResolver;
    }

    public function fetchLabel(
        string $parcelId,
        string $shopId,
        string $salesChannelId
    ): LabelDataInterface {
        $dhl = $this->apiResolver->getApi($shopId, $salesChannelId);

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
