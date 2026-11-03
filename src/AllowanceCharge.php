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
    private bool $chargeIndicator = false;
    private $allowanceChargeReasonCode;
    private ?string $allowanceChargeReason = null;
    private ?float $multiplierFactorNumeric = null;
    private ?float $baseAmount = null;
    private ?float $amount = null;
    private ?TaxCategory $taxCategory = null;

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
     * @return int|string|null
     */
    public function getAllowanceChargeReasonCode()
    {
        return $this->allowanceChargeReasonCode;
    }

    /**
     * @param int|string|null $allowanceChargeReasonCode
     * @return static
     */
    public function setAllowanceChargeReasonCode($allowanceChargeReasonCode)
    {
        $this->allowanceChargeReasonCode = $allowanceChargeReasonCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAllowanceChargeReason(): ?string
    {
        return $this->allowanceChargeReason;
    }

    /**
     * @param string|null $allowanceChargeReason
     * @return static
     */
    public function setAllowanceChargeReason(?string $allowanceChargeReason)
    {
        $this->allowanceChargeReason = $allowanceChargeReason;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getMultiplierFactorNumeric(): ?float
    {
        return $this->multiplierFactorNumeric;
    }

    /**
     * @param float|null $multiplierFactorNumeric
     * @return static
     */
    public function setMultiplierFactorNumeric(?float $multiplierFactorNumeric)
    {
        $this->multiplierFactorNumeric = $multiplierFactorNumeric;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getBaseAmount(): ?float
    {
        return $this->baseAmount;
    }

    /**
     * @param float|null $baseAmount
     * @return static
     */
    public function setBaseAmount(?float $baseAmount)
    {
        $this->baseAmount = $baseAmount;
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
     * @return TaxCategory|null
     */
    public function getTaxCategory(): ?TaxCategory
    {
        return $this->taxCategory;
    }

    /**
     * @param TaxCategory|null $taxCategory
     * @return static
     */
    public function setTaxCategory(?TaxCategory $taxCategory)
    {
        $this->taxCategory = $taxCategory;
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
                Schema::CBC . 'MultiplierFactorNumeric' => NumberFormatter::format($this->multiplierFactorNumeric)
            ]);
        }

        $writer->write([
            [
                'name' => Schema::CBC . 'Amount',
                'value' => NumberFormatter::format($this->amount, 2),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ],
        ]);

        if ($this->baseAmount !== null) {
            $writer->write([
                [
                    'name' => Schema::CBC . 'BaseAmount',
                    'value' => NumberFormatter::format($this->baseAmount, 2),
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
    }

    /**
     * Parse allowance charge reason code, converting numeric strings to int, preserving non-numeric strings.
     * @param string|null $value
     * @return int|string|null
     */
    private static function parseAllowanceChargeReasonCode(?string $value)
    {
        if ($value === null) {
            return null;
        }

        if (is_numeric($value)) {
            return intval($value);
        }

        return $value;
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

        $allowanceChargeReasonCode = self::parseAllowanceChargeReasonCode(
            ReaderHelper::getTagValue(Schema::CBC . 'AllowanceChargeReasonCode', $collection)
        );

        $multiplierFactorNumericTagValue = ReaderHelper::getTagValue(
            Schema::CBC . 'MultiplierFactorNumeric',
            $collection
        );
        $multiplierFactorNumeric = $multiplierFactorNumericTagValue !== null
            ? floatval(ReaderHelper::getTagValue(Schema::CBC . 'MultiplierFactorNumeric', $collection))
            : null;

        $baseAmount = ReaderHelper::getTagValue(Schema::CBC . 'BaseAmount', $collection) !== null
            ? floatval(ReaderHelper::getTagValue(Schema::CBC . 'BaseAmount', $collection))
            : null;

        $amount = ReaderHelper::getTagValue(Schema::CBC . 'Amount', $collection) !== null
            ? floatval(ReaderHelper::getTagValue(Schema::CBC . 'Amount', $collection))
            : null;

        return (new static())
            ->setChargeIndicator(ReaderHelper::getTagValue(Schema::CBC . 'ChargeIndicator', $collection) === 'true')
            ->setAllowanceChargeReasonCode($allowanceChargeReasonCode)
            ->setAllowanceChargeReason(ReaderHelper::getTagValue(Schema::CBC . 'AllowanceChargeReason', $collection))
            ->setMultiplierFactorNumeric($multiplierFactorNumeric)
            ->setBaseAmount($baseAmount)
            ->setAmount($amount)
            ->setTaxCategory(ReaderHelper::getTagValue(Schema::CAC . 'TaxCategory', $collection));
    }
}
