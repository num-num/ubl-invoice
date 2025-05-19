<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class LegalMonetaryTotal implements XmlSerializable, XmlDeserializable
{
    private $lineExtensionAmount = 0;
    private $taxExclusiveAmount = 0;
    private $taxInclusiveAmount = 0;
    private $allowanceTotalAmount = 0;
    private $chargeTotalAmount = 0;
    private $prepaidAmount;
    private $payableAmount = 0;
    private $payableRoundingAmount;

    /**
     * @return float
     */
    public function getLineExtensionAmount(): ?float
    {
        return $this->lineExtensionAmount;
    }

    /**
     * @param float $lineExtensionAmount
     * @return static
     */
    public function setLineExtensionAmount(?float $lineExtensionAmount)
    {
        $this->lineExtensionAmount = $lineExtensionAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxExclusiveAmount(): ?float
    {
        return $this->taxExclusiveAmount;
    }

    /**
     * @param float $taxExclusiveAmount
     * @return static
     */
    public function setTaxExclusiveAmount(?float $taxExclusiveAmount)
    {
        $this->taxExclusiveAmount = $taxExclusiveAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxInclusiveAmount(): ?float
    {
        return $this->taxInclusiveAmount;
    }

    /**
     * @param float $taxInclusiveAmount
     * @return static
     */
    public function setTaxInclusiveAmount(?float $taxInclusiveAmount)
    {
        $this->taxInclusiveAmount = $taxInclusiveAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getAllowanceTotalAmount(): ?float
    {
        return $this->allowanceTotalAmount;
    }

    /**
     * @param float $allowanceTotalAmount
     * @return static
     */
    public function setAllowanceTotalAmount(?float $allowanceTotalAmount)
    {
        $this->allowanceTotalAmount = $allowanceTotalAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getChargeTotalAmount(): ?float
    {
        return $this->chargeTotalAmount;
    }

    /**
     * @param float $chargeTotalAmount
     * @return static
     */
    public function setChargeTotalAmount(?float $chargeTotalAmount)
    {
        $this->chargeTotalAmount = $chargeTotalAmount;
        return $this;
    }

    /**
     * @return ?float
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
     * @return float
     */
    public function getPayableAmount(): ?float
    {
        return $this->payableAmount;
    }

    /**
     * @param float $payableAmount
     * @return static
     */
    public function setPayableAmount(?float $payableAmount)
    {
        $this->payableAmount = $payableAmount;
        return $this;
    }

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
                'value'      => NumberFormatter::format($this->lineExtensionAmount),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]

            ],
            [
                'name'       => Schema::CBC . 'TaxExclusiveAmount',
                'value'      => NumberFormatter::format($this->taxExclusiveAmount),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]

            ],
            [
                'name'       => Schema::CBC . 'TaxInclusiveAmount',
                'value'      => NumberFormatter::format($this->taxInclusiveAmount),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]

            ],
            [
                'name'       => Schema::CBC . 'AllowanceTotalAmount',
                'value'      => NumberFormatter::format($this->allowanceTotalAmount),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]

            ],
            [
                'name'       => Schema::CBC . 'ChargeTotalAmount',
                'value'      => NumberFormatter::format($this->chargeTotalAmount),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]

            ]
        ]);

        if ($this->prepaidAmount !== null) {
            $writer->write([
                [
                    'name'       => Schema::CBC . 'PrepaidAmount',
                    'value'      => NumberFormatter::format($this->prepaidAmount),
                    'attributes' => [
                        'currencyID' => Generator::$currencyID
                    ]
                ]
            ]);
        }

        if ($this->payableRoundingAmount !== null) {
            $writer->write([
                [
                    'name' => Schema::CBC . 'PayableRoundingAmount',
                    'value' => NumberFormatter::format($this->payableRoundingAmount),
                    'attributes' => [
                        'currencyID' => Generator::$currencyID
                    ]
                ],
            ]);
        }

        $writer->write([
            [
                'name'       => Schema::CBC . 'PayableAmount',
                'value'      => NumberFormatter::format($this->payableAmount),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ],
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
            ->setLineExtensionAmount(isset($keyValues[Schema::CBC . 'LineExtensionAmount']) ? floatval($keyValues[Schema::CBC . 'LineExtensionAmount']) : null)
            ->setTaxExclusiveAmount(isset($keyValues[Schema::CBC . 'TaxExclusiveAmount']) ? floatval($keyValues[Schema::CBC . 'TaxExclusiveAmount']) : null)
            ->setTaxInclusiveAmount(isset($keyValues[Schema::CBC . 'TaxInclusiveAmount']) ? floatval($keyValues[Schema::CBC . 'TaxInclusiveAmount']) : null)
            ->setAllowanceTotalAmount(isset($keyValues[Schema::CBC . 'AllowanceTotalAmount']) ? floatval($keyValues[Schema::CBC . 'AllowanceTotalAmount']) : null)
            ->setChargeTotalAmount(isset($keyValues[Schema::CBC . 'ChargeTotalAmount']) ? floatval($keyValues[Schema::CBC . 'ChargeTotalAmount']) : null)
            ->setPrepaidAmount(isset($keyValues[Schema::CBC . 'PrepaidAmount']) ? floatval($keyValues[Schema::CBC . 'PrepaidAmount']) : null)
            ->setPayableRoundingAmount(isset($keyValues[Schema::CBC . 'PayableAmount']) ? floatval($keyValues[Schema::CBC . 'PayableAmount']) : null)
            ->setPayableAmount(isset($keyValues[Schema::CBC . 'PayableAmount']) ? floatval($keyValues[Schema::CBC . 'PayableAmount']) : null)
        ;
    }
}
