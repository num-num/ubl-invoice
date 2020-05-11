<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class AllowanceCharge implements XmlSerializable
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
    public function isChargeIndicator()
    {
        return $this->chargeIndicator;
    }

    /**
     * @param bool $chargeIndicator
     * @return AllowanceCharge
     */
    public function setChargeIndicator(bool $chargeIndicator)
    {
        $this->chargeIndicator = $chargeIndicator;
        return $this;
    }

    /**
     * @return int
     */
    public function getAllowanceChargeReasonCode()
    {
        return $this->allowanceChargeReasonCode;
    }

    /**
     * @param int $allowanceChargeReasonCode
     * @return AllowanceCharge
     */
    public function setAllowanceChargeReasonCode(int $allowanceChargeReasonCode)
    {
        $this->allowanceChargeReasonCode = $allowanceChargeReasonCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAllowanceChargeReason()
    {
        return $this->allowanceChargeReason;
    }

    /**
     * @param string $allowanceChargeReason
     * @return AllowanceCharge
     */
    public function setAllowanceChargeReason(string $allowanceChargeReason)
    {
        $this->allowanceChargeReason = $allowanceChargeReason;
        return $this;
    }

    /**
     * @return int
     */
    public function getMultiplierFactorNumeric()
    {
        return $this->multiplierFactorNumeric;
    }

    /**
     * @param int $multiplierFactorNumeric
     * @return AllowanceCharge
     */
    public function setMultiplierFactorNumeric(int $multiplierFactorNumeric)
    {
        $this->multiplierFactorNumeric = $multiplierFactorNumeric;
        return $this;
    }

    /**
     * @return float
     */
    public function getBaseAmount()
    {
        return $this->baseAmount;
    }

    /**
     * @param float $baseAmount
     * @return AllowanceCharge
     */
    public function setBaseAmount(float $baseAmount)
    {
        $this->baseAmount = $baseAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return AllowanceCharge
     */
    public function setAmount(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return TaxCategory
     */
    public function getTaxCategory()
    {
        return $this->taxCategory;
    }

    /**
     * @param TaxCategory $taxCategory
     * @return AllowanceCharge
     */
    public function setTaxCategory(TaxCategory $taxCategory)
    {
        $this->taxCategory = $taxCategory;
        return $this;
    }

    /**
     * @return TaxCategory
     */
    public function getTaxtotal()
    {
        return $this->taxTotal;
    }

    /**
     * @param TaxTotal $taxTotal
     * @return AllowanceCharge
     */
    public function setTaxtotal(TaxTotal $taxTotal)
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
    public function xmlSerialize(Writer $writer)
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
                'name' => Schema::CBC . 'Amount',
                'value' => $this->amount,
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ],
        ]);

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

        if ($this->baseAmount !== null) {
            $writer->write([
                [
                    'name' => Schema::CBC . 'BaseAmount',
                    'value' => $this->baseAmount,
                    'attributes' => [
                        'currencyID' => Generator::$currencyID
                    ]
                ]
            ]);
        }
    }
}
