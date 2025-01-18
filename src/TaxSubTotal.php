<?php

namespace NumNum\UBL;

use InvalidArgumentException;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class TaxSubTotal implements XmlSerializable, XmlDeserializable
{
    private $taxableAmount;
    private $taxAmount;
    private $taxCategory;
    private $percent;

    /**
     * @return mixed
     */
    public function getTaxableAmount(): ?float
    {
        return $this->taxableAmount;
    }

    /**
     * @param mixed $taxableAmount
     * @return static
     */
    public function setTaxableAmount(?float $taxableAmount)
    {
        $this->taxableAmount = $taxableAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxAmount(): ?float
    {
        return $this->taxAmount;
    }

    /**
     * @param mixed $taxAmount
     * @return static
     */
    public function setTaxAmount(?float $taxAmount)
    {
        $this->taxAmount = $taxAmount;
        return $this;
    }

    /**
     * @return TaxCategory
     */
    public function getTaxCategory(): ?TaxCategory
    {
        return $this->taxCategory;
    }

    /**
     * @param TaxCategory $taxCategory
     * @return static
     */
    public function setTaxCategory(?TaxCategory $taxCategory)
    {
        $this->taxCategory = $taxCategory;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercent(): ?float
    {
        return $this->percent;
    }

    /**
     * @param float $percent
     * @return static
     */
    public function setPercent(?float $percent)
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * The validate function that is called during xml writing to valid the data of the object.
     *
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
     * @return void
     */
    public function validate()
    {
        if ($this->taxableAmount === null) {
            throw new InvalidArgumentException('Missing taxsubtotal taxableAmount');
        }

        if ($this->taxAmount === null) {
            throw new InvalidArgumentException('Missing taxsubtotal taxamount');
        }

        if ($this->taxCategory === null) {
            throw new InvalidArgumentException('Missing taxsubtotal taxcategory');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        $this->validate();

        $writer->write([
            [
                'name'       => Schema::CBC . 'TaxableAmount',
                'value'      => number_format($this->taxableAmount, 2, '.', ''),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ],
            [
                'name'       => Schema::CBC . 'TaxAmount',
                'value'      => number_format($this->taxAmount, 2, '.', ''),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ]
        ]);

        if ($this->percent !== null) {
            $writer->write([
                Schema::CBC . 'Percent' => $this->percent
            ]);
        }

        $writer->write([
            Schema::CAC . 'TaxCategory' => $this->taxCategory
        ]);
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
            ->setTaxableAmount(isset($keyValues[Schema::CBC . 'TaxableAmount']) ? floatval($keyValues[Schema::CBC . 'TaxableAmount']) : null)
            ->setTaxAmount(isset($keyValues[Schema::CBC . 'TaxAmount']) ? floatval($keyValues[Schema::CBC . 'TaxAmount']) : null)
            ->setTaxCategory($keyvalues[Schema::CAC . 'TaxCategory'] ?? null)
            ->setPercent(isset($keyValues[Schema::CBC . 'Percent']) ? floatval($keyValues[Schema::CBC . 'Percent']) : null)
        ;
    }
}
