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

    public const UNCL5305 = 'UNCL5305';

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        if ($this->id !== null) {
            return $this->id;
        }

        if ($this->getPercent() !== null) {
            if ($this->getPercent() >= 21) {
                return 'S';
            } elseif ($this->getPercent() <= 21 && $this->getPercent() >= 6) {
                return 'AA';
            } else {
                return 'Z';
            }
        }

        return null;
    }

    /**
     * @param string|null $id
     * @return ClassifiedTaxCategory
     */
    public function setId(?string $id): ClassifiedTaxCategory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ClassifiedTaxCategory
     */
    public function setName(?string $name): ClassifiedTaxCategory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPercent(): ?float
    {
        return $this->percent;
    }

    /**
     * @param float|null $percent
     * @return ClassifiedTaxCategory
     */
    public function setPercent(?float $percent): ClassifiedTaxCategory
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * @return TaxScheme|null
     */
    public function getTaxScheme(): ?TaxScheme
    {
        return $this->taxScheme;
    }

    /**
     * @param TaxScheme|null $taxScheme
     * @return ClassifiedTaxCategory
     */
    public function setTaxScheme(?TaxScheme $taxScheme): ClassifiedTaxCategory
    {
        $this->taxScheme = $taxScheme;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSchemeID(): ?string
    {
        return $this->schemeID;
    }

    /**
     * @param string|null $id
     * @return ClassifiedTaxCategory
     */
    public function setSchemeID(?string $id): ClassifiedTaxCategory
    {
        $this->schemeID = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSchemeName(): ?string
    {
        return $this->schemeName;
    }

    /**
     * @param string|null $name
     * @return ClassifiedTaxCategory
     */
    public function setSchemeName(?string $name): ClassifiedTaxCategory
    {
        $this->schemeName = $name;
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
            [
                'name' => Schema::CBC . 'ID',
                'value' => $this->getId(),
                'attributes' => $schemeAttributes

            ],
        ]);

        if ($this->percent !== null) {
            $writer->write([
                Schema::CBC . 'Percent' => number_format($this->percent, 2, '.', ''),
            ]);
        }

        if ($this->name !== null) {
            $writer->write([
                Schema::CBC . 'Name' => $this->name,
            ]);
        }

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
