<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class TaxScheme implements XmlSerializable, XmlDeserializable
{
    private $id;
    private $name;
    private $taxTypeCode;
    private $currencyCode;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     */
    public function setId(?string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName(?string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxTypeCode(): ?string
    {
        return $this->taxTypeCode;
    }

    /**
     * @param string $taxTypeCode
     * @return static
     */
    public function setTaxTypeCode(?string $taxTypeCode)
    {
        $this->taxTypeCode = $taxTypeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     * @return static
     */
    public function setCurrencyCode(?string $currencyCode)
    {
        $this->currencyCode = $currencyCode;
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
        if ($this->id !== null) {
            $writer->write([
                Schema::CBC . 'ID' => $this->id
            ]);
        }
        if ($this->name !== null) {
            $writer->write([
                Schema::CBC . 'Name' => $this->name
            ]);
        }
        if ($this->taxTypeCode !== null) {
            $writer->write([
                Schema::CBC . 'TaxTypeCode' => $this->taxTypeCode
            ]);
        }
        if ($this->currencyCode !== null) {
            $writer->write([
                Schema::CBC . 'CurrencyCode' => $this->currencyCode
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
        $keyValues = keyValue($reader);

        return (new static())
            ->setId($keyValues[Schema::CBC . 'ID'] ?? null)
            ->setName($keyValues[Schema::CBC . 'Name'] ?? null)
            ->setTaxTypeCode($keyValues[Schema::CBC . 'TaxTypeCode'] ?? null)
            ->setCurrencyCode($keyValues[Schema::CBC . 'CurrencyCode'] ?? null)
        ;
    }
}
