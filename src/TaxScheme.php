<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class TaxScheme implements XmlSerializable
{
    private $id;
    private $taxTypeCode;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return int
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxTypeCode()
    {
        return $this->taxTypeCode;
    }

    /**
     * @param mixed $taxTypeCode
     * @return int
     */
    public function setTaxTypeCode($taxTypeCode)
    {
        $this->taxTypeCode = $taxTypeCode;
        return $this;
    }

    public function xmlSerialize(Writer $writer)
    {
        $writer->write([
            Schema::CBC . 'ID' => $this->id,
            Schema::CBC . 'TaxTypeCode' => $this->taxTypeCode
        ]);
    }
}
