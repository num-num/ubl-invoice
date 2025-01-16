<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class PaymentTerms implements XmlSerializable, XmlDeserializable
{
    private $note;
    private $settlementDiscountPercent;
    private $amount;
    private $settlementPeriod;

    /**
     * @return string
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string $note
     * @return PaymentTerms
     */
    public function setNote(?string $note): PaymentTerms
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return float
     */
    public function getSettlementDiscountPercent(): ?float
    {
        return $this->settlementDiscountPercent;
    }

    /**
     * @param float $settlementDiscountPercent
     * @return PaymentTerms
     */
    public function setSettlementDiscountPercent(?float $settlementDiscountPercent): PaymentTerms
    {
        $this->settlementDiscountPercent = $settlementDiscountPercent;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return PaymentTerms
     */
    public function setAmount(?float $amount): PaymentTerms
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return SettlementPeriod
     */
    public function getSettlementPeriod(): ?SettlementPeriod
    {
        return $this->settlementPeriod;
    }

    /**
     * @param SettlementPeriod $settlementPeriod
     * @return PaymentTerms
     */
    public function setSettlementPeriod(?SettlementPeriod $settlementPeriod): PaymentTerms
    {
        $this->settlementPeriod = $settlementPeriod;
        return $this;
    }

    public function xmlSerialize(Writer $writer): void
    {
        if ($this->note !== null) {
            $writer->write([ Schema::CBC . 'Note' => $this->note ]);
        }

        if ($this->settlementDiscountPercent !== null) {
            $writer->write([ Schema::CBC . 'SettlementDiscountPercent' => $this->settlementDiscountPercent ]);
        }

        if ($this->amount !== null) {
            $writer->write([
                [
                    'name' => Schema::CBC . 'Amount',
                    'value' => number_format($this->amount, 2, '.', ''),
                    'attributes' => [
                        'currencyID' => 'EUR'
                    ]
                ]
            ]);
        }

        if ($this->settlementPeriod !== null) {
            $writer->write([ Schema::CAC . 'SettlementPeriod' => $this->settlementPeriod ]);
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $xml
     * @return PaymentTerms
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        return (new self())
            ->setNote($keyValues[Schema::CBC . 'Note'] ?? null)
            ->setSettlementDiscountPercent($keyValues[Schema::CBC . 'SettlementDiscountPercent'] ?? null)
            ->setAmount($keyValues[Schema::CBC . 'Amount'] ?? null)
            ->setSettlementPeriod($keyValues[Schema::CAC . 'SettlementPeriod'] ?? null);
    }
}
