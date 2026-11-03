<?php

namespace NumNum\UBL;

use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;

use function Sabre\Xml\Deserializer\mixedContent;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class ClassifiedTaxCategory implements XmlSerializable, XmlDeserializable
{
    private ?string $id = null;
    private ?string $name = null;
    private ?float $percent = null;
    private ?TaxScheme $taxScheme = null;
    private ?string $taxExemptionReason = null;
    private ?string $taxExemptionReasonCode = null;
    private ?string $schemeID = null;
    private ?string $schemeName = null;

    /**
     * @return string|null
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
     * @param string|null $id
     * @return static
     */
    public function setId(?string $id)
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
     * @return static
     */
    public function setName(?string $name)
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
     * @return static
     */
    public function setPercent(?float $percent)
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
     * @return static
     */
    public function setTaxScheme(?TaxScheme $taxScheme)
    {
        $this->taxScheme = $taxScheme;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSchemeId(): ?string
    {
        return $this->schemeID;
    }

    /**
     * @param string|null $id
     * @return static
     */
    public function setSchemeId(?string $id)
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
     * @return static
     */
    public function setSchemeName(?string $name)
    {
        $this->schemeName = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTaxExemptionReason(): ?string
    {
        return $this->taxExemptionReason;
    }

    /**
     * @param string|null $taxExemptionReason
     * @return static
     */
    public function setTaxExemptionReason(?string $taxExemptionReason)
    {
        $this->taxExemptionReason = $taxExemptionReason;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTaxExemptionReasonCode(): ?string
    {
        return $this->taxExemptionReasonCode;
    }

    /**
     * @param string|null $taxExemptionReasonCode
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
     * @return void
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
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
            'name'       => Schema::CBC . 'ID',
            'value'      => $this->getId(),
            'attributes' => $schemeAttributes
        ]);

        if ($this->name !== null) {
            $writer->write([
                Schema::CBC . 'Name' => $this->name,
            ]);
        }

        if ($this->percent !== null) {
            $writer->write([
                Schema::CBC . 'Percent' => NumberFormatter::format($this->percent, 2)
            ]);
        }

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
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = mixedContent($reader);
        $collection = new ArrayCollection($mixedContent);

        $idTag = ReaderHelper::getTag(Schema::CBC . 'ID', $collection);
        $percentTag = ReaderHelper::getTag(Schema::CBC . 'Percent', $collection);
        $nameTag = ReaderHelper::getTag(Schema::CBC . 'Name', $collection);
        $taxSchemeTag = ReaderHelper::getTag(Schema::CAC . 'TaxScheme', $collection);
        $taxExemptionReasonCodeTag = ReaderHelper::getTag(Schema::CBC . 'TaxExemptionReasonCode', $collection);
        $taxExemptionReasonTag = ReaderHelper::getTag(Schema::CBC . 'TaxExemptionReason', $collection);

        return (new static())
            ->setId($idTag['value'] ?? null)
            ->setPercent(isset($percentTag) ? floatval($percentTag['value']) : null)
            ->setName(isset($nameTag) ? $nameTag['value'] : null)
            ->setTaxScheme($taxSchemeTag['value'] ?? null)
            ->setTaxExemptionReason($taxExemptionReasonTag['value'] ?? null)
            ->setTaxExemptionReasonCode($taxExemptionReasonCodeTag['value'] ?? null)
            ->setSchemeId($idTag['attributes']['schemeID'] ?? null)
            ->setSchemeName($idTag['attributes']['schemeName'] ?? null);
    }
}
