<?php

namespace NumNum\UBL;

use InvalidArgumentException;

use function Sabre\Xml\Deserializer\mixedContent;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class TaxTotal implements XmlSerializable, XmlDeserializable
{
    private $taxAmount;
    private $currencyCode;
    private $taxSubTotals = [];

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    /**
     * Set the TaxAmount currency, or null to use the generator currency.
     *
     * @return static
     */
    public function setCurrencyCode(?string $currencyCode)
    {
        $this->currencyCode = $currencyCode;
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
     * @return array<TaxSubTotal>
     */
    public function getTaxSubTotals(): array
    {
        return $this->taxSubTotals;
    }

    /**
     * @param array<TaxSubTotal> $taxSubTotal
     * @return static
     */
    public function setTaxSubTotals(array $taxSubTotals)
    {
        $this->taxSubTotals = $taxSubTotals;
        return $this;
    }

    /**
     * @param TaxSubTotal $taxSubTotal
     * @return static
     */
    public function addTaxSubTotal(TaxSubTotal $taxSubTotal)
    {
        $this->taxSubTotals[] = $taxSubTotal;
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
        if ($this->taxAmount === null) {
            throw new InvalidArgumentException('Missing taxtotal taxamount');
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
                'name'       => Schema::CBC . 'TaxAmount',
                'value'      => NumberFormatter::format($this->taxAmount, 2),
                'attributes' => [
                    'currencyID' => $this->currencyCode ?? Generator::$currencyID
                ]
            ],
        ]);

        foreach ($this->taxSubTotals as $taxSubTotal) {
            $writer->write([Schema::CAC . 'TaxSubtotal' => $taxSubTotal]);
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = mixedContent($reader);
        $collection = new ArrayCollection($mixedContent);

        $taxAmount = ReaderHelper::getTag(Schema::CBC . 'TaxAmount', $collection);
        $taxSubTotals = ReaderHelper::getArrayValue(Schema::CAC . 'TaxSubtotal', $collection);

        return (new static())
            ->setTaxAmount(isset($taxAmount) ? floatval($taxAmount['value']) : null)
            ->setCurrencyCode($taxAmount['attributes']['currencyID'] ?? null)
            ->setTaxSubTotals($taxSubTotals);
    }
}
