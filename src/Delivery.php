<?php

namespace NumNum\UBL;

use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class Delivery implements XmlSerializable, XmlDeserializable
{
    private ?DateTime $actualDeliveryDate = null;
    private ?Address $deliveryLocation = null;
    private ?Party $deliveryParty = null;

    /**
     * @return DateTime|null
     */
    public function getActualDeliveryDate(): ?DateTime
    {
        return $this->actualDeliveryDate;
    }

    /**
     * @param DateTime|null $actualDeliveryDate
     * @return static
     */
    public function setActualDeliveryDate(?DateTime $actualDeliveryDate)
    {
        $this->actualDeliveryDate = $actualDeliveryDate;
        return $this;
    }

    /**
     * @return Address|null
     */
    public function getDeliveryLocation()
    {
        return $this->deliveryLocation;
    }

    /**
     * @param Address|null $deliveryLocation
     * @return static
     */
    public function setDeliveryLocation(?Address $deliveryLocation)
    {
        $this->deliveryLocation = $deliveryLocation;
        return $this;
    }

    /**
     * @return Party|null
     */
    public function getDeliveryParty()
    {
        return $this->deliveryParty;
    }

    /**
     * @param Party|null $deliveryParty
     * @return static
     */
    public function setDeliveryParty(?Party $deliveryParty)
    {
        $this->deliveryParty = $deliveryParty;
        return $this;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        if ($this->actualDeliveryDate != null) {
            $writer->write([
               Schema::CBC . 'ActualDeliveryDate' => $this->actualDeliveryDate->format('Y-m-d')
            ]);
        }
        if ($this->deliveryLocation != null) {
            $writer->write([
               Schema::CAC . 'DeliveryLocation' => [ Schema::CAC . 'Address' => $this->deliveryLocation ]
            ]);
        }
        if ($this->deliveryParty != null) {
            $writer->write([
               Schema::CAC . 'DeliveryParty' => $this->deliveryParty
            ]);
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        $actualDeliveryDate = isset($keyValues[Schema::CBC . 'ActualDeliveryDate'])
            ? Carbon::parse($keyValues[Schema::CBC . 'ActualDeliveryDate'])->toDateTime()
            : null;

        $deliveryLocation = ReaderHelper::getTag(
            Schema::CAC . 'Address',
            new ArrayCollection($keyValues[Schema::CAC . 'DeliveryLocation'] ?? [])
        );

        $deliveryParty = ReaderHelper::getTag(
            Schema::CAC . 'Party',
            new ArrayCollection($keyValues[Schema::CAC . 'DeliveryParty'] ?? [])
        );

        return (new static())
            ->setActualDeliveryDate($actualDeliveryDate)
            ->setDeliveryLocation($deliveryLocation['value'] ?? null)
            ->setDeliveryParty($deliveryParty['value'] ?? null);
    }
}
