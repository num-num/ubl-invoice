<?php
/**
 * Created by PhpStorm.
 * User: bram.vaneijk
 * Date: 27-10-2016
 * Time: 10:43
 */

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class AllowanceCharge implements XmlSerializable {
    /**
     * @var boolean
     */
    private $chargeIndicator;
    /**
     * @var int
     */
    private $allowanceChargeReasonCode;
    /**
     * @var string
     */
    private $allowanceChargeReason;
    /**
     * @var int
     */
    private $multiplierFactorNumeric;
    /**
     * @var float
     */
    private $baseAmount;
    /**
     * @var float
     */
    private $amount;

    /**
     * @return boolean
     */
    public function isChargeIndicator() {
        return $this->chargeIndicator;
    }

    /**
     * @param boolean $chargeIndicator
     * @return AllowanceCharge
     */
    public function setChargeIndicator($chargeIndicator) {
        $this->chargeIndicator = $chargeIndicator;
        return $this;
    }

    /**
     * @return int
     */
    public function getAllowanceChargeReasonCode() {
        return $this->allowanceChargeReasonCode;
    }

    /**
     * @param int $allowanceChargeReasonCode
     * @return AllowanceCharge
     */
    public function setAllowanceChargeReasonCode($allowanceChargeReasonCode) {
        $this->allowanceChargeReasonCode = $allowanceChargeReasonCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAllowanceChargeReason() {
        return $this->allowanceChargeReason;
    }

    /**
     * @param string $allowanceChargeReason
     * @return AllowanceCharge
     */
    public function setAllowanceChargeReason($allowanceChargeReason) {
        $this->allowanceChargeReason = $allowanceChargeReason;
        return $this;
    }

    /**
     * @return int
     */
    public function getMultiplierFactorNumeric() {
        return $this->multiplierFactorNumeric;
    }

    /**
     * @param int $multiplierFactorNumeric
     * @return AllowanceCharge
     */
    public function setMultiplierFactorNumeric($multiplierFactorNumeric) {
        $this->multiplierFactorNumeric = $multiplierFactorNumeric;
        return $this;
    }

    /**
     * @return float
     */
    public function getBaseAmount() {
        return $this->baseAmount;
    }

    /**
     * @param float $baseAmount
     * @return AllowanceCharge
     */
    public function setBaseAmount($baseAmount) {
        $this->baseAmount = $baseAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return AllowanceCharge
     */
    public function setAmount($amount) {
        $this->amount = $amount;
        return $this;
    }


    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    function xmlSerialize(Writer $writer) {
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