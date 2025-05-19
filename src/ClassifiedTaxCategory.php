<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

use InvalidArgumentException;

class ClassifiedTaxCategory implements XmlSerializable
{
    private $id;
    private $name;
    private $percent;
    private $taxScheme;
    private $taxExemptionReason;
    private $taxExemptionReasonCode;
    private $schemeID;
    private $schemeName;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        if (!empty($this->id)) {
            return $this->id;
        }

        if ($this->getPercent() !== null) {
            return ($this->getPercent() > 0)
                ? UNCL5305::STANDARD_RATE
                : UNCL5305::ZERO_RATED_GOODS;
        }

        return null;
    }

    /**
     * @param string $id
     * @return ClassifiedTaxCategory
     */
    public function setId(?string $id): ClassifiedTaxCategory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ClassifiedTaxCategory
     */
    public function setName(?string $name): ClassifiedTaxCategory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercent(): ?float
    {
        return $this->percent;
    }

    /**
     * @param float $percent
     * @return ClassifiedTaxCategory
     */
    public function setPercent(?float $percent): ClassifiedTaxCategory
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * @return TaxScheme
     */
    public function getTaxScheme(): ?TaxScheme
    {
        return $this->taxScheme;
    }

    /**
     * @param TaxScheme $taxScheme
     * @return ClassifiedTaxCategory
     */
    public function setTaxScheme(?TaxScheme $taxScheme): ClassifiedTaxCategory
    {
        $this->taxScheme = $taxScheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getSchemeID(): ?string
    {
        return $this->schemeID;
    }

    /**
     * @param string $id
     * @return ClassifiedTaxCategory
     */
    public function setSchemeID(?string $id): ClassifiedTaxCategory
    {
        $this->schemeID = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSchemeName(): ?string
    {
        return $this->schemeName;
    }

    /**
     * @param string $name
     * @return ClassifiedTaxCategory
     */
    public function setSchemeName(?string $name): ClassifiedTaxCategory
    {
        $this->schemeName = $name;
        return $this;
    }

    /**
     * @return string
     */
	public function getTaxExemptionReason(): ?string
	{
		return $this->taxExemptionReason;
	}

    /**
     * @param string $taxExemptionReason
     * @return ClassifiedTaxCategory
     */
	public function setTaxExemptionReason(?string $taxExemptionReason): ClassifiedTaxCategory
	{
		$this->taxExemptionReason = $taxExemptionReason;
		return $this;
	}

    /**
     * @return string
     */
	public function getTaxExemptionReasonCode(): ?string
	{
		return $this->taxExemptionReasonCode;
	}

    /**
     * @param string $taxExemptionReasonCode
     * @return ClassifiedTaxCategory
     */
    public function setTaxExemptionReasonCode(?string $taxExemptionReasonCode): ClassifiedTaxCategory
	{
		$this->taxExemptionReasonCode = $taxExemptionReasonCode;
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
        if ($this->getId() === null) {
            throw new InvalidArgumentException('Missing taxcategory id');
        }

        if ($this->getPercent() === null) {
            throw new InvalidArgumentException('Missing taxcategory percent');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        $this->validate();

        $schemeAttributes = [];
        if ($this->schemeID !== null) {
            $schemeAttributes['schemeID'] = $this->schemeID;
        }
        if ($this->schemeName !== null) {
            $schemeAttributes['schemeName'] = $this->schemeName;
        }

        $writer->write([
            'name' => Schema::CBC . 'ID',
            'value' => $this->getId(),
            'attributes' => $schemeAttributes
        ]);

        if ($this->name !== null) {
            $writer->write([
                Schema::CBC . 'Name' => $this->name,
            ]);
        }

        $writer->write([
            Schema::CBC . 'Percent' => NumberFormatter::format($this->percent)
        ]);

        if ($this->taxExemptionReasonCode !== null) {
            $writer->write([
                Schema::CBC . 'TaxExemptionReasonCode' => $this->taxExemptionReasonCode,
                Schema::CBC . 'TaxExemptionReason' => $this->taxExemptionReason,
            ]);
        }

        if ($this->taxScheme !== null) {
            $writer->write([Schema::CAC . 'TaxScheme' => $this->taxScheme]);
        } else {
            $writer->write([
                Schema::CAC . 'TaxScheme' => null,
            ]);
        }
    }
}
