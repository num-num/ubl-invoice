<?php

namespace NumNum\UBL;

use InvalidArgumentException;

use function Sabre\Xml\Deserializer\mixedContent;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class ClassifiedTaxCategory implements XmlSerializable, XmlDeserializable
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
     * @return static
     */
    public function setId(?string $id)
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
     * @return static
     */
    public function setName(?string $name)
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
     * @return static
     */
    public function setPercent(?float $percent)
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
     * @return static
     */
    public function setTaxScheme(?TaxScheme $taxScheme)
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
     * @return static
     */
    public function setSchemeID(?string $id)
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
     * @return static
     */
    public function setSchemeName(?string $name)
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
     * @return static
     */
    public function setTaxExemptionReason(?string $taxExemptionReason)
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
     * @return static
     */
    public function setTaxExemptionReasonCode(?string $taxExemptionReasonCode)
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
            'name'       => Schema::CBC . 'ID',
            'value'      => $this->getId(),
            'attributes' => $schemeAttributes
        ]);

        if ($this->name !== null) {
            $writer->write([
                Schema::CBC . 'Name' => $this->name,
            ]);
        }

        $writer->write([
            Schema::CBC . 'Percent' => number_format($this->percent, 2, '.', ''),
        ]);

        if ($this->taxExemptionReasonCode !== null) {
            $writer->write([
                Schema::CBC . 'TaxExemptionReasonCode' => $this->taxExemptionReasonCode,
                Schema::CBC . 'TaxExemptionReason'     => $this->taxExemptionReason,
            ]);
        }

        if ($this->taxScheme !== null) {
            $writer->write([
                Schema::CAC . 'TaxScheme' => $this->taxScheme
                ]);
        } else {
            $writer->write([
                Schema::CAC . 'TaxScheme' => null,
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

        $idTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'ID'))[0] ?? null;
        $percentTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'Percent'))[0] ?? null;
        $nameTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'Name'))[0] ?? null;
        $taxSchemeTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'TaxScheme'))[0] ?? null;
        $taxExemptionReasonCodeTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'TaxExemptionReasonCode'))[0] ?? null;
        $taxExemptionReasonTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'TaxExemptionReason'))[0] ?? null;

        return (new static())
            ->setId($idTag['value'] ?? null)
            ->setPercent(isset($percentTag) ? floatval($percentTag['value']) : null)
            ->setName(isset($nameTag) ? $nameTag['value'] : null)
            ->setTaxScheme($taxSchemeTag['value'] ?? null)
            ->setTaxExemptionReason($taxExemptionReasonTag['value'] ?? null)
            ->setTaxExemptionReasonCode($taxExemptionReasonCodeTag['value'] ?? null)
            ->setSchemeID($idTag['attributes']['schemeID'] ?? null)
            ->setSchemeName($idTag['attributes']['schemeName'] ?? null)
        ;
    }
}
