<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class AllowanceCharge implements XmlSerializable, XmlDeserializable
{
    private $chargeIndicator;
    private $allowanceChargeReasonCode;
    private $allowanceChargeReason;
    private $multiplierFactorNumeric;
    private $baseAmount;
    private $amount;
    private $taxTotal;
    private $taxCategory;

    /**
     * @return bool
     */
    public function isChargeIndicator(): bool
    {
        return $this->chargeIndicator;
    }

    /**
     * @param bool $chargeIndicator
     * @return static
     */
    public function setChargeIndicator(bool $chargeIndicator)
    {
        $this->chargeIndicator = $chargeIndicator;
        return $this;
    }

    /**
     * @return int
     */
    public function getAllowanceChargeReasonCode(): ?int
    {
        return $this->allowanceChargeReasonCode;
    }

    /**
     * @param int $allowanceChargeReasonCode
     * @return static
     */
    public function setAllowanceChargeReasonCode(?int $allowanceChargeReasonCode)
    {
        $this->allowanceChargeReasonCode = $allowanceChargeReasonCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAllowanceChargeReason(): ?string
    {
        return $this->allowanceChargeReason;
    }

    /**
     * @param string $allowanceChargeReason
     * @return static
     */
    public function setAllowanceChargeReason(?string $allowanceChargeReason)
    {
        $this->allowanceChargeReason = $allowanceChargeReason;
        return $this;
    }

    /**
     * @return float
     */
    public function getMultiplierFactorNumeric(): ?float
    {
        return $this->multiplierFactorNumeric;
    }

    /**
     * @param float $multiplierFactorNumeric
     * @return static
     */
    public function setMultiplierFactorNumeric(?float $multiplierFactorNumeric)
    {
        $this->multiplierFactorNumeric = $multiplierFactorNumeric;
        return $this;
    }

    /**
     * @return float
     */
    public function getBaseAmount(): ?float
    {
        return $this->baseAmount;
    }

    /**
     * @param float $baseAmount
     * @return static
     */
    public function setBaseAmount(?float $baseAmount)
    {
        $this->baseAmount = $baseAmount;
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
     * @return static
     */
    public function setAmount(?float $amount)
    {
        $this->amount = $amount;
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
     * @return TaxCategory
     */
    public function getTaxtotal(): ?TaxTotal
    {
        return $this->taxTotal;
    }

    /**
     * @param TaxTotal $taxTotal
     * @return static
     */
    public function setTaxtotal(?TaxTotal $taxTotal)
    {
        $this->taxTotal = $taxTotal;
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
        $writer->write([
            Schema::CBC . 'ChargeIndicator' => $this->chargeIndicator ? 'true' : 'false',
        ]);

        if ($this->allowanceChargeReasonCode !== null) {
            $writer->write([
                Schema::CBC . 'AllowanceChargeReasonCode' => $this->allowanceChargeReasonCode
            ]);
        }

        if ($this->allowanceChargeReason !== null) {
            $writer->write([
                Schema::CBC . 'AllowanceChargeReason' => $this->allowanceChargeReason
            ]);
        }

        if ($this->multiplierFactorNumeric !== null) {
            $writer->write([
                Schema::CBC . 'MultiplierFactorNumeric' => $this->multiplierFactorNumeric
            ]);
        }

        $writer->write([
            [
                'name'       => Schema::CBC . 'Amount',
                'value'      => NumberFormatter::format($this->amount),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ],
        ]);

        if ($this->baseAmount !== null) {
            $writer->write([
                [
                    'name'       => Schema::CBC . 'BaseAmount',
                    'value'      => NumberFormatter::format($this->baseAmount),
                    'attributes' => [
                        'currencyID' => Generator::$currencyID
                    ]
                ]
            ]);
        }

        if ($this->taxCategory !== null) {
            $writer->write([
                Schema::CAC . 'TaxCategory' => $this->taxCategory
            ]);
        }

        if ($this->taxTotal !== null) {
            $writer->write([
                Schema::CAC . 'TaxTotal' => $this->taxTotal
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

        return (new static())
            ->setChargeIndicator(ReaderHelper::getTagValue(Schema::CBC . 'ChargeIndicator', $collection) === 'true')
            ->setAllowanceChargeReasonCode(ReaderHelper::getTagValue(Schema::CBC . 'AllowanceChargeReasonCode', $collection) !== null ? intval(ReaderHelper::getTagValue(Schema::CBC . 'AllowanceChargeReasonCode', $collection)) : null)
            ->setAllowanceChargeReason(ReaderHelper::getTagValue(Schema::CBC . 'AllowanceChargeReason', $collection))
            ->setMultiplierFactorNumeric(ReaderHelper::getTagValue(Schema::CBC . 'MultiplierFactorNumeric', $collection) !== null ? floatval(ReaderHelper::getTagValue(Schema::CBC . 'MultiplierFactorNumeric', $collection)) : null)
            ->setBaseAmount(ReaderHelper::getTagValue(Schema::CBC . 'BaseAmount', $collection) !== null ? floatval(ReaderHelper::getTagValue(Schema::CBC . 'BaseAmount', $collection)) : null)
            ->setAmount(ReaderHelper::getTagValue(Schema::CBC . 'Amount', $collection) !== null ? floatval(ReaderHelper::getTagValue(Schema::CBC . 'Amount', $collection)) : null)
            ->setTaxTotal(ReaderHelper::getTagValue(Schema::CAC . 'TaxTotal', $collection));
    }
}
