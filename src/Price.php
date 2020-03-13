<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Price implements XmlSerializable
{
    private $priceAmount;
    private $baseQuantity;
    private $unitCode = UnitCode::UNIT;

    /**
     * @return mixed
     */
    public function getPriceAmount()
    {
        return $this->priceAmount;
    }

    /**
     * @param mixed $priceAmount
     * @return Price
     */
    public function setPriceAmount($priceAmount)
    {
        $this->priceAmount = $priceAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBaseQuantity()
    {
        return $this->baseQuantity;
    }

    /**
     * @param mixed $baseQuantity
     * @return Price
     */
    public function setBaseQuantity($baseQuantity)
    {
        $this->baseQuantity = $baseQuantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnitCode()
    {
        return $this->unitCode;
    }

    /**
     * @param mixed $unitCode
     * See also: src/UnitCode.php
     * @return Price
     */
    public function setUnitCode(string $unitCode)
    {
        $this->unitCode = $unitCode;
        return $this;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer)
    {
        $writer->write([
            [
                'name' => Schema::CBC . 'PriceAmount',
                'value' => $this->priceAmount,
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ],
            [
                'name' => Schema::CBC . 'BaseQuantity',
                'value' => $this->baseQuantity,
                'attributes' => [
                    'unitCode' => $this->unitCode
                ]
            ]
        ]);
    }
}
