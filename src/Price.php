<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class Price implements XmlSerializable, XmlDeserializable
{
    private $priceAmount;
    private $baseQuantity;
    private $unitCode = UnitCode::UNIT;
    private $unitCodeListId;
    private $allowanceCharge;

    /**
     * @return float
     */
    public function getPriceAmount(): ?float
    {
        return $this->priceAmount;
    }

    /**
     * @param float $priceAmount
     * @return static
     */
    public function setPriceAmount(?float $priceAmount)
    {
        $this->priceAmount = $priceAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getBaseQuantity(): ?float
    {
        return $this->baseQuantity;
    }

    /**
     * @param float $baseQuantity
     * @return static
     */
    public function setBaseQuantity(?float $baseQuantity)
    {
        $this->baseQuantity = $baseQuantity;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnitCode(): ?string
    {
        return $this->unitCode;
    }

    /**
     * @param string $unitCode
     *                         See also: src/UnitCode.php
     * @return static
     */
    public function setUnitCode(?string $unitCode)
    {
        $this->unitCode = $unitCode;
        return $this;
    }


    /**
     * @return string
     */
    public function getUnitCodeListId(): ?string
    {
        return $this->unitCodeListId;
    }

    /**
     * @param string $unitCodeListId
     * @return static
     */
    public function setUnitCodeListId(?string $unitCodeListId)
    {
        $this->unitCodeListId = $unitCodeListId;
        return $this;
    }

    /**
     * @return AllowanceCharge
     */
    public function getAllowanceCharge(): ?AllowanceCharge
    {
        return $this->allowanceCharge;
    }

    /**
     * @param AllowanceCharge $allowanceCharge
     * @return static
     */
    public function setAllowanceCharge(?AllowanceCharge $allowanceCharge)
    {
        $this->allowanceCharge = $allowanceCharge;
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
        $baseQuantityAttributes = [
            'unitCode' => $this->unitCode,
        ];

        if (!empty($this->getUnitCodeListId())) {
            $baseQuantityAttributes['unitCodeListID'] = $this->getUnitCodeListId();
        }

        $writer->write([
            [
                'name'       => Schema::CBC . 'PriceAmount',
                'value'      => NumberFormatter::format($this->priceAmount),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ],
            [
                'name'       => Schema::CBC . 'BaseQuantity',
                'value'      => NumberFormatter::format($this->baseQuantity),
                'attributes' => $baseQuantityAttributes
            ]
        ]);

        if ($this->allowanceCharge !== null) {
            $writer->write([
                Schema::CAC . 'AllowanceCharge' => $this->allowanceCharge,
            ]);
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $xml
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = mixedContent($reader);
        $collection = new ArrayCollection($mixedContent);

        $priceAmountTag = ReaderHelper::getTag(Schema::CBC . 'PriceAmount', $collection);
        $baseQuantityTag = ReaderHelper::getTag(Schema::CBC . 'BaseQuantity', $collection);
        $allowanceChargeTag = ReaderHelper::getTag(Schema::CAC . 'AllowanceCharge', $collection);

        return (new Price())
            ->setPriceAmount(isset($priceAmountTag) ? floatval($priceAmountTag['value']) : null)
            ->setBaseQuantity(isset($baseQuantityTag) ? floatval($baseQuantityTag['value']) : null)
            ->setUnitCode($baseQuantityTag['attributes']['unitCode'] ?? null)
            ->setUnitCodeListId($baseQuantityTag['attributes']['unitCodeListID'] ?? null)
            ->setAllowanceCharge($allowanceChargeTag['value'] ?? null)
        ;
    }
}
