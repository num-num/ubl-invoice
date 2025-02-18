<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class LegalEntity implements XmlSerializable, XmlDeserializable
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
     * @return static
     */
    public function setRegistrationName(?string $registrationName)
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
     * @return static
     */
    public function setCompanyId(?string $companyId, $attributes = null)
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
     * @return static
    */
    public function setCompanyLegalForm(?string $legalForm, $attributes = null)
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
        if ($this->registrationName !== null) {
            $writer->write([
                Schema::CBC . 'RegistrationName' => $this->registrationName,
            ]);
        }
        if ($this->companyId !== null) {
            $writer->write([
                [
                    'name'       => Schema::CBC . 'CompanyID',
                    'value'      => $this->companyId,
                    'attributes' => $this->companyIdAttributes,
                ],
            ]);
        }
        if ($this->companyLegalForm !== null) {
            $writer->write([
                [
                    'name'       => Schema::CBC . 'CompanyLegalForm',
                    'value'      => $this->companyLegalForm,
                    'attributes' => $this->companyLegalFormAttributes
                ]
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
        $collection = new ArrayCollection($mixedContent);

        $registrationName = ReaderHelper::getTag(Schema::CBC . 'RegistrationName', $collection);
        $companyId = ReaderHelper::getTag(Schema::CBC . 'CompanyID', $collection);
        $companyLegalForm = ReaderHelper::getTag(Schema::CBC . 'CompanyLegalForm', $collection);

        return (new static())
            ->setRegistrationName($registrationName['value'] ?? null)
            ->setCompanyId(
                $companyId['value'] ?? null,
                $companyId['attributes'] ?? null
            )
            ->setCompanyLegalForm(
                $companyLegalForm['value'] ?? null,
                $companyLegalForm['attributes'] ?? null
            )
        ;
    }
}
