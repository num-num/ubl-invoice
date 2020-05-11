<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class LegalEntity implements XmlSerializable
{
    private $registrationName;
    private $companyId;

    /**
     * @return mixed
     */
    public function getRegistrationName()
    {
        return $this->registrationName;
    }

    /**
     * @param string $registrationName
     * @return LegalEntity
     */
    public function setRegistrationName($registrationName)
    {
        $this->registrationName = $registrationName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param string $companyId
     * @return LegalEntity
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
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
            Schema::CBC . 'RegistrationName' => $this->registrationName,
            Schema::CBC . 'CompanyID' => $this->companyId
        ]);
    }
}
