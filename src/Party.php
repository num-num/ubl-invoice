<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Party implements XmlSerializable
{
    private $name;
    private $postalAddress;
    private $physicalLocation;
    private $contact;
    private $companyId;
    private $taxCompanyId;
    private $taxCompanyName;
    private $taxScheme;
    private $legalEntity;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Party
     */
    public function setName(?string $name): Party
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Address
     */
    public function getPostalAddress(): ?Address
    {
        return $this->postalAddress;
    }

    /**
     * @param Address $postalAddress
     * @return Party
     */
    public function setPostalAddress(?Address $postalAddress): Party
    {
        $this->postalAddress = $postalAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }

    /**
     * @param string $companyId
     * @return Party
     */
    public function setCompanyId(?string $companyId): Party
    {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxCompanyId(): ?string
    {
        return $this->taxCompanyId;
    }

    /**
     * @param string $taxCompanyId
     * @return Party
     */
    public function setTaxCompanyId(?string $companyId): Party
    {
        $this->taxCompanyId = $companyId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxCompanyName(): ?string
    {
        return $this->taxCompanyName;
    }

    /**
     * @param string $taxCompanyName
     * @return Party
     */
    public function setTaxCompanyName(?string $companyName): Party
    {
        $this->taxCompanyName = $companyName;
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
     * @return Party
     */
    public function setTaxScheme(?TaxScheme $taxScheme): Party
    {
        $this->taxScheme = $taxScheme;
        return $this;
    }

    /**
     * @return LegalEntity
     */
    public function getLegalEntity(): ?LegalEntity
    {
        return $this->legalEntity;
    }

    /**
     * @param LegalEntity $legalEntity
     * @return Party
     */
    public function setLegalEntity(?LegalEntity $legalEntity): Party
    {
        $this->legalEntity = $legalEntity;
        return $this;
    }

    /**
     * @return Address
     */
    public function getPhysicalLocation(): ?Address
    {
        return $this->physicalLocation;
    }

    /**
     * @param Address $physicalLocation
     * @return Party
     */
    public function setPhysicalLocation(?Address $physicalLocation): Party
    {
        $this->physicalLocation = $physicalLocation;
        return $this;
    }

    /**
     * @return Contact
     */
    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    /**
     * @param Contact $contact
     * @return Party
     */
    public function setContact(?Contact $contact): Party
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer)
    {
        $writer->write([
            Schema::CAC . 'PartyName' => [
                Schema::CBC . 'Name' => $this->name
            ],
            Schema::CAC . 'PostalAddress' => $this->postalAddress
        ]);

        if ($this->physicalLocation !== null) {
            $writer->write([
               Schema::CAC . 'PhysicalLocation' => [Schema::CAC . 'Address' => $this->physicalLocation]
            ]);
        }

        if ($this->taxScheme !== null) {
            $partyTaxScheme = [];

            if ($this->taxCompanyName !== null) {
                $partyTaxScheme[Schema::CBC . 'RegistrationName'] = $this->taxCompanyName;
            }

            if ($this->taxCompanyId !== null) {
                $partyTaxScheme[Schema::CBC . 'CompanyID'] = $this->taxCompanyId;
            }

            $partyTaxScheme[Schema::CAC . 'TaxScheme'] = $this->taxScheme;

            $writer->write([
                Schema::CAC . 'PartyTaxScheme' => $partyTaxScheme
            ]);
        }

        if ($this->contact !== null) {
            $writer->write([
                Schema::CAC . 'Contact' => $this->contact
            ]);
        }

        if ($this->legalEntity !== null) {
            $writer->write([
                Schema::CAC . 'PartyLegalEntity' => $this->legalEntity
            ]);
        }
    }
}
