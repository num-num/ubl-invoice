<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class LegalEntity implements XmlSerializable
{
    private $registrationName;
    private $companyId;
    private $companyIdAttributes;
    private $companyLegalForm;
    private $companyLegalFormAttributes;

    /**
     * @return string
     */
    public function getRegistrationName(): ?string
    {
        return $this->registrationName;
    }

    /**
     * @param string $registrationName
     * @return LegalEntity
     */
    public function setRegistrationName(?string $registrationName): LegalEntity
    {
        $this->registrationName = $registrationName;
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
     * @return LegalEntity
     */
    public function setCompanyId(?string $companyId, $attributes = null): LegalEntity
    {
        $this->companyId = $companyId;
        if (isset($attributes)) {
            $this->companyIdAttributes = $attributes;
        }
        return $this;
    }

    /**
    *
     * @param string $legalForm
     * @return LegalEntity
    */
    public function setCompanyLegalForm(?string $legalForm, $attributes = null): LegalEntity
    {
        $this->companyLegalForm = $legalForm;
        if (isset($attributes)) {
            $this->companyLegalFormAttributes = $attributes;
        }
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
            Schema::CBC . 'RegistrationName' => $this->registrationName,
        ]);
        if ($this->companyId !== null) {
            $writer->write([
                [
                    'name' => Schema::CBC . 'CompanyID',
                    'value' => $this->companyId,
                    'attributes' => $this->companyIdAttributes,
                ],
            ]);
        }
        if ($this->companyLegalForm !== null) {
            $writer->write([
                [
                    'name' => Schema::CBC . 'CompanyLegalForm',
                    'value' => $this->companyLegalForm,
                    'attributes' => $this->companyLegalFormAttributes
                ]
            ]);
        }
    }
}
