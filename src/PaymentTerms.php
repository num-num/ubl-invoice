<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class PaymentTerms implements XmlSerializable, XmlDeserializable
{
    private ?string $note = null;
    private ?float $settlementDiscountPercent = null;
    private ?float $amount = null;
    private ?SettlementPeriod $settlementPeriod = null;

    /**
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string|null $note
     * @return static
     */
    public function setNote(?string $note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getSettlementDiscountPercent(): ?float
    {
        return $this->settlementDiscountPercent;
    }

    /**
     * @param float|null $settlementDiscountPercent
     * @return static
     */
    public function setSettlementDiscountPercent(?float $settlementDiscountPercent)
    {
        $this->settlementDiscountPercent = $settlementDiscountPercent;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float|null $amount
     * @return static
     */
    public function setAmount(?float $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return SettlementPeriod|null
     */
    public function getSettlementPeriod(): ?SettlementPeriod
    {
        return $this->settlementPeriod;
    }

    /**
     * @param SettlementPeriod|null $settlementPeriod
     * @return static
     */
    public function setSettlementPeriod(?SettlementPeriod $settlementPeriod)
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
                    'name'       => Schema::CBC . 'Amount',
                    'value'      => NumberFormatter::format($this->amount, 2),
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
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        return (new static())
            ->setNote($keyValues[Schema::CBC . 'Note'] ?? null)
            ->setSettlementDiscountPercent($keyValues[Schema::CBC . 'SettlementDiscountPercent'] ?? null)
            ->setAmount($keyValues[Schema::CBC . 'Amount'] ?? null)
            ->setSettlementPeriod($keyValues[Schema::CAC . 'SettlementPeriod'] ?? null);
    }
}
