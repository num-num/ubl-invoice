<?php

namespace NumNum\UBL;

use InvalidArgumentException;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class PartyTaxScheme implements XmlSerializable, XmlDeserializable
{
    private ?string $registrationName = null;
    private ?string $companyId = null;
    private ?TaxScheme $taxScheme = null;

    /**
     * @return string|null
     */
    public function getRegistrationName(): ?string
    {
        return $this->registrationName;
    }

    /**
     * @param string|null $registrationName
     * @return static
     */
    public function setRegistrationName(?string $registrationName)
    {
        $this->registrationName = $registrationName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }

    /**
     * @param string|null $companyId
     * @return static
     */
    public function setCompanyId(?string $companyId)
    {
        $this->companyId = $companyId;
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
     * The validate function that is called during xml writing to valid the data of the object.
     *
     * @return void
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
     */
    public function validate()
    {
        if ($this->taxScheme === null) {
            throw new InvalidArgumentException('Missing TaxScheme');
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
        if ($this->registrationName !== null) {
            $writer->write([
                Schema::CBC . 'RegistrationName' => $this->registrationName
            ]);
        }
        if ($this->companyId !== null) {
            $writer->write([
                Schema::CBC . 'CompanyID' => $this->companyId
            ]);
        }
        if ($this->taxScheme !== null) {
            $writer->write([
                Schema::CAC . 'TaxScheme' => $this->taxScheme
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
        $keyValues = keyValue($reader);

        return (new static())
            ->setRegistrationName($keyValues[Schema::CBC . 'RegistrationName'] ?? null)
            ->setCompanyId($keyValues[Schema::CBC . 'CompanyID'] ?? null)
            ->setTaxScheme($keyValues[Schema::CAC . 'TaxScheme'] ?? null);
    }
}
