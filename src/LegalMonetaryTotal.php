<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class LegalMonetaryTotal implements XmlSerializable, XmlDeserializable
{
    private ?float $lineExtensionAmount = 0;
    private ?float $taxExclusiveAmount = 0;
    private ?float $taxInclusiveAmount = 0;
    private ?float $allowanceTotalAmount = null;
    private ?float $chargeTotalAmount = null;
    private ?float $prepaidAmount = null;
    private ?float $payableAmount = 0;
    private ?float $payableRoundingAmount = null;

    /**
     * @return float|null
     */
    public function getLineExtensionAmount(): ?float
    {
        return $this->lineExtensionAmount;
    }

    /**
     * @param float|null $lineExtensionAmount
     * @return static
     */
    public function setLineExtensionAmount(?float $lineExtensionAmount)
    {
        $this->lineExtensionAmount = $lineExtensionAmount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTaxExclusiveAmount(): ?float
    {
        return $this->taxExclusiveAmount;
    }

    /**
     * @param float|null $taxExclusiveAmount
     * @return static
     */
    public function setTaxExclusiveAmount(?float $taxExclusiveAmount)
    {
        $this->taxExclusiveAmount = $taxExclusiveAmount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTaxInclusiveAmount(): ?float
    {
        return $this->taxInclusiveAmount;
    }

    /**
     * @param float|null $taxInclusiveAmount
     * @return static
     */
    public function setTaxInclusiveAmount(?float $taxInclusiveAmount)
    {
        $this->taxInclusiveAmount = $taxInclusiveAmount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAllowanceTotalAmount(): ?float
    {
        return $this->allowanceTotalAmount;
    }

    /**
     * @param float|null $allowanceTotalAmount
     * @return static
     */
    public function setAllowanceTotalAmount(?float $allowanceTotalAmount)
    {
        $this->allowanceTotalAmount = $allowanceTotalAmount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getChargeTotalAmount(): ?float
    {
        return $this->chargeTotalAmount;
    }

    /**
     * @param float|null $chargeTotalAmount
     * @return static
     */
    public function setChargeTotalAmount(?float $chargeTotalAmount)
    {
        $this->chargeTotalAmount = $chargeTotalAmount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrepaidAmount(): ?float
    {
        return $this->prepaidAmount;
    }

    /**
     * @param ?float $prepaidAmount
     * @return static
     */
    public function setPrepaidAmount(?float $prepaidAmount)
    {
        $this->prepaidAmount = $prepaidAmount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPayableAmount(): ?float
    {
        return $this->payableAmount;
    }

    /**
     * @param float|null $payableAmount
     * @return static
     */
    public function setPayableAmount(?float $payableAmount)
    {
        $this->payableAmount = $payableAmount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPayableRoundingAmount(): ?float
    {
        return $this->payableRoundingAmount;
    }

    /**
     * @param float|null $payableRoundingAmount
     * @return LegalMonetaryTotal
     */
    public function setPayableRoundingAmount(?float $payableRoundingAmount): LegalMonetaryTotal
    {
        $this->payableRoundingAmount = $payableRoundingAmount;
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
            [
                'name'       => Schema::CBC . 'LineExtensionAmount',
                'value'      => NumberFormatter::format($this->lineExtensionAmount, 2),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]

            ],
            [
                'name'       => Schema::CBC . 'TaxExclusiveAmount',
                'value'      => NumberFormatter::format($this->taxExclusiveAmount, 2),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]

            ],
            [
                'name'       => Schema::CBC . 'TaxInclusiveAmount',
                'value'      => NumberFormatter::format($this->taxInclusiveAmount, 2),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]

            ]
        ]);

        if ($this->allowanceTotalAmount !== null) {
            $writer->write([
                [
                    'name'       => Schema::CBC . 'AllowanceTotalAmount',
                    'value'      => NumberFormatter::format($this->allowanceTotalAmount, 2),
                    'attributes' => [
                        'currencyID' => Generator::$currencyID
                    ]
                ]
            ]);
        }

        if ($this->chargeTotalAmount !== null) {
            $writer->write([
                [
                    'name'       => Schema::CBC . 'ChargeTotalAmount',
                    'value'      => NumberFormatter::format($this->chargeTotalAmount, 2),
                    'attributes' => [
                        'currencyID' => Generator::$currencyID
                    ]
                ]
            ]);
        }

        if ($this->prepaidAmount !== null) {
            $writer->write([
                [
                    'name'       => Schema::CBC . 'PrepaidAmount',
                    'value'      => NumberFormatter::format($this->prepaidAmount, 2),
                    'attributes' => [
                        'currencyID' => Generator::$currencyID
                    ]
                ]
            ]);
        }

        if ($this->payableRoundingAmount !== null) {
            $writer->write([
                [
                    'name'       => Schema::CBC . 'PayableRoundingAmount',
                    'value'      => NumberFormatter::format($this->payableRoundingAmount, 2),
                    'attributes' => [
                        'currencyID' => Generator::$currencyID
                    ]
                ],
            ]);
        }

        $writer->write([
            [
                'name'       => Schema::CBC . 'PayableAmount',
                'value'      => NumberFormatter::format($this->payableAmount, 2),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ],
        ]);
    }


    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        $lineExtensionAmount = isset($keyValues[Schema::CBC . 'LineExtensionAmount'])
            ? floatval($keyValues[Schema::CBC . 'LineExtensionAmount'])
            : null;

        $taxExclusiveAmount = isset($keyValues[Schema::CBC . 'TaxExclusiveAmount'])
            ? floatval($keyValues[Schema::CBC . 'TaxExclusiveAmount'])
            : null;

        $taxInclusiveAmount = isset($keyValues[Schema::CBC . 'TaxInclusiveAmount'])
            ? floatval($keyValues[Schema::CBC . 'TaxInclusiveAmount'])
            : null;

        $allowanceTotalAmount = isset($keyValues[Schema::CBC . 'AllowanceTotalAmount'])
            ? floatval($keyValues[Schema::CBC . 'AllowanceTotalAmount'])
            : null;

        $chargeTotalAmount = isset($keyValues[Schema::CBC . 'ChargeTotalAmount'])
            ? floatval($keyValues[Schema::CBC . 'ChargeTotalAmount'])
            : null;

        $prepaidAmount = isset($keyValues[Schema::CBC . 'PrepaidAmount'])
            ? floatval($keyValues[Schema::CBC . 'PrepaidAmount'])
            : null;

        $payableAmount = isset($keyValues[Schema::CBC . 'PayableAmount'])
            ? floatval($keyValues[Schema::CBC . 'PayableAmount'])
            : null;

        return (new static())
            ->setLineExtensionAmount($lineExtensionAmount)
            ->setTaxExclusiveAmount($taxExclusiveAmount)
            ->setTaxInclusiveAmount($taxInclusiveAmount)
            ->setAllowanceTotalAmount($allowanceTotalAmount)
            ->setChargeTotalAmount($chargeTotalAmount)
            ->setPrepaidAmount($prepaidAmount)
            ->setPayableRoundingAmount($payableAmount)
            ->setPayableAmount($payableAmount);
    }
}
