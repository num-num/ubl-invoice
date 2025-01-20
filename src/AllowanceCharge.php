<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

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
                'value'      => $this->amount,
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
                    'name'       => Schema::CBC . 'BaseAmount',
                    'value'      => $this->baseAmount,
                    'attributes' => [
                        'currencyID' => Generator::$currencyID
                    ]
                ]
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

        $chargeIndicatorTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'ChargeIndicator'))[0] ?? null;
        $allowanceChargeReasonCodeTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'AllowanceChargeReasonCode'))[0] ?? null;
        $allowanceChargeReasonTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'AllowanceChargeReason'))[0] ?? null;
        $multiplierFactorNumericTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'MultiplierFactorNumeric'))[0] ?? null;
        $baseAmountTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'BaseAmount'))[0] ?? null;
        $amountTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'Amount'))[0] ?? null;
        $taxTotalTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'TaxTotal'))[0] ?? null;

        return (new static())
            ->setChargeIndicator(isset($chargeIndicatorTag) ? $chargeIndicatorTag['value'] === 'true' : null)
            ->setAllowanceChargeReasonCode(isset($allowanceChargeReasonCodeTag) ? intval($allowanceChargeReasonCodeTag['value']) : null)
            ->setAllowanceChargeReason(isset($allowanceChargeReasonTag) ? $allowanceChargeReasonTag['value'] : null)
            ->setMultiplierFactorNumeric(isset($multiplierFactorNumericTag) ? floatval($multiplierFactorNumericTag['value']) : null)
            ->setBaseAmount(isset($baseAmountTag) ? floatval($baseAmountTag['value']) : null)
            ->setAmount(isset($amountTag) ? floatval($amountTag['value']) : null)
            ->setTaxTotal($taxTotalTag['value'] ?? null)
        ;
    }
}
