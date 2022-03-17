<?php

namespace BitBag\ShopwareAppSkeleton\API;

use Alexcherniatin\DHL\DHL24;
use Alexcherniatin\DHL\Exceptions\InvalidStructureException;
use Alexcherniatin\DHL\Exceptions\SoapException;
use Alexcherniatin\DHL\Structures\Address;
use Alexcherniatin\DHL\Structures\PaymentData;
use Alexcherniatin\DHL\Structures\Piece;
use Alexcherniatin\DHL\Structures\ReceiverAddress;
use Alexcherniatin\DHL\Structures\ServiceDefinition;
use Alexcherniatin\DHL\Structures\ShipmentFullData;
use BitBag\ShopwareAppSkeleton\Entity\ConfigInterface;
use BitBag\ShopwareAppSkeleton\Repository\ConfigRepositoryInterface;

final class CreateShipment implements CreateShipmentInterface
{
    /** @var ConfigRepositoryInterface */
    private ConfigRepositoryInterface $configRepository;

    public function __construct(ConfigRepositoryInterface $congigRepository)
    {
        $this->configRepository = $congigRepository;
    }

    /**
     * @throws InvalidStructureException
     * @throws SoapException
     */
    public function createShipments($shippingAddress, $shopId, $customerEmail)
    {
        /** @var ConfigInterface $config */
        $config = $this->configRepository->findOneBy(['shop' => $shopId]);

        if (!$config) {
            return;
        }

        $dhl = new DHL24($config->getUsername(), $config->getPassword(), $config->getAccountNumber(), true);

        preg_match('/^([^\d]*[^\d\s]) *(\d.*)$/', $shippingAddress['street'], $street);

        $addressStructure = (new Address())
            ->setName($config->getName())
            ->setPostalCode($config->getPostalCode())
            ->setCity($config->getCity())
            ->setStreet($config->getStreet())
            ->setHouseNumber($config->getHouseNumber())
            ->setContactPhone($config->getPhoneNumber())
            ->setContactEmail($config->getEmail())
            ->structure();

        //Receiver address
        $receiverAddressStructure = (new ReceiverAddress())
            ->setAddressType(ReceiverAddress::ADDRESS_TYPE_B)
            ->setCountry('PL')
            ->setName($shippingAddress['firstName'].' '.$shippingAddress['lastName'])
            ->setPostalCode($shippingAddress['zipcode'])
            ->setCity($shippingAddress['city'])
            ->setStreet($street[1])
            ->setHouseNumber($street[2])
            ->setContactPhone($shippingAddress['phoneNumber'])
            ->setContactEmail($customerEmail)
            ->structure();

        //Package settings
        $pieceStructure = (new Piece())
            ->setType(Piece::TYPE_PACKAGE)
            ->setWidth(25)
            ->setHeight(25)
            ->setLength(25)
            ->setWeight(3)
            ->setQuantity(1)
            ->setNonStandard(false)
            ->structure();

        //Payment
        $paymentStructure = (new PaymentData())
            ->setPaymentMethod(PaymentData::PAYMENT_METHOD_BANK_TRANSFER)
            ->setPayerType(PaymentData::PAYER_TYPE_SHIPPER)
            ->setAccountNumber('6000000')
            ->structure();

        //Service
        $serviceDefinitionStructure = (new ServiceDefinition())
            ->setProduct(ServiceDefinition::PRODUCT_DOMESTIC_SHIPMENT)
            ->setInsurance(true)
            ->setInsuranceValue(200)
            ->structure();

        //Group all data to shipment structure
        $shipmentFullDataStructure = (new ShipmentFullData())
            ->setShipper($addressStructure)
            ->setReceiver($receiverAddressStructure)
            ->setPieceList(
                [
                    $pieceStructure,
                ]
            )
            ->setPayment($paymentStructure)
            ->setService($serviceDefinitionStructure)
            ->setShipmentDate(\date(ShipmentFullData::DATE_FORMAT, \strtotime('2022-03-18')))
            ->setContent('Some content')
            ->setSkipRestrictionCheck(true)
            ->structure();

        try {
            $result = $dhl->createShipments($shipmentFullDataStructure);
            //file_put_contents('dumb_error.json', json_encode($result));
        } catch (\Throwable $th) {
            //file_put_contents('dumb_error.json', $th->getMessage());
            echo $th->getMessage();
        }
    }
}
