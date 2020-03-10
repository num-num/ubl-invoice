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
    private $taxScheme;
    private $legalEntity;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Party
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Address
     */
    public function getPostalAddress()
    {
        return $this->postalAddress;
    }

    /**
     * @param Address $postalAddress
     * @return Party
     */
    public function setPostalAddress($postalAddress)
    {
        $this->postalAddress = $postalAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param string $companyId
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @param TaxScheme $taxScheme.
     * @return mixed
     */
    public function getTaxScheme()
    {
        return $this->taxScheme;
    }

    /**
     * @param TaxScheme $taxScheme
     */
    public function setTaxScheme(TaxScheme $taxScheme)
    {
        $this->taxScheme = $taxScheme;
    }

    /**
     * @return LegalEntity
     */
    public function getLegalEntity()
    {
        return $this->legalEntity;
    }

    /**
     * @param LegalEntity $legalEntity
     * @return Party
     */
    public function setLegalEntity(LegalEntity $legalEntity)
    {
        $this->legalEntity = $legalEntity;
        return $this;
    }

    /**
     * @return Address
     */
    public function getPhysicalLocation()
    {
        return $this->physicalLocation;
    }

    /**
     * @param Address $physicalLocation
     * @return Party
     */
    public function setPhysicalLocation(Address $physicalLocation)
    {
        $this->physicalLocation = $physicalLocation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     * @return Party
     */
    public function setContact($contact)
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

        if ($this->physicalLocation) {
            $writer->write([
               Schema::CAC . 'PhysicalLocation' => [Schema::CAC . 'Address' => $this->physicalLocation]
            ]);
        }

        if ($this->taxScheme) {
            $writer->write([
                Schema::CAC . 'PartyTaxScheme' => [
                    Schema::CBC . 'RegistrationName' => $this->name,
                    Schema::CBC . 'CompanyID' => $this->companyId,
                    Schema::CAC . 'TaxScheme' => $this->taxScheme
                ]
            ]);
        }

        if ($this->contact) {
            $writer->write([
                Schema::CAC . 'Contact' => $this->contact
            ]);
        }
    }
}
