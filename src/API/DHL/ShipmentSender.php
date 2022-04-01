<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\API\DHL;

use Alexcherniatin\DHL\Exceptions\SoapException;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Exception\ShipmentException;
use BitBag\ShopwareAppSkeleton\Factory\PackageFactory;
use BitBag\ShopwareAppSkeleton\Model\OrderDataInterface;
use BitBag\ShopwareAppSkeleton\Saver\LabelSaverInterface;

final class ShipmentSender implements ShipmentSenderInterface
{
    private ApiResolverInterface $apiResolver;

    private PackageFactory $packageFactory;

    private LabelSaverInterface $labelSaver;

    public function __construct(
        ApiResolverInterface $apiResolver,
        PackageFactory $packageFactory,
        LabelSaverInterface $labelSaver
    ) {
        $this->apiResolver = $apiResolver;
        $this->packageFactory = $packageFactory;
        $this->labelSaver = $labelSaver;
    }

    public function createShipments(
        OrderDataInterface $orderData,
        ConfigInterface $config
    ): void {
        $dhl = $this->apiResolver->getApi($orderData->getShopId());

        $shipmentFullDataStructure = $this->packageFactory->create($config, $orderData);

        try {
            $res = $dhl->createShipments($shipmentFullDataStructure);
            $this->labelSaver->save($orderData->getShopId(), $res['shipmentId'], $orderData->getOrderId());
        } catch (SoapException $th) {
            throw new ShipmentException($th->getMessage());
        }
    }
}
