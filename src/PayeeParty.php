<?php

namespace NumNum\UBL;

use Doctrine\Common\Collections\ArrayCollection;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use function Sabre\Xml\Deserializer\mixedContent;

class PayeeParty implements XmlSerializable, XmlDeserializable
{
    private $partyIdentificationId;
    private $partyIdentificationSchemeId;
    private $partyName;
    private $partyLegalEntityCompanyId;
    private $partyLegalEntityCompanySchemeId;

    /**
     * @return string
     */
    public function getPartyIdentificationId(): ?string
    {
        return $this->partyIdentificationId;
    }

    /**
     * @param string|null $partyIdentificationId
     * @return static
     */
    public function setPartyIdentificationId(?string $partyIdentificationId)
    {
        $this->partyIdentificationId = $partyIdentificationId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPartyIdentificationSchemeId(): ?string
    {
        return $this->partyIdentificationSchemeId;
    }

    /**
     * @param  string|null  $partyIdentificationSchemeId
     * @return static
     */
    public function setPartyIdentificationSchemeId(?string $partyIdentificationSchemeId)
    {
        $this->partyIdentificationSchemeId = $partyIdentificationSchemeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPartyName(): ?string
    {
        return $this->partyName;
    }

    /**
     * @param string|null $partyName
     *
     * @return static
     */
    public function setPartyName(?string $partyName)
    {
        $this->partyName = $partyName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPartyLegalEntityCompanyId(): ?string
    {
        return $this->partyLegalEntityCompanyId;
    }

    /**
     * @param string|null $partyLegalEntityCompanyId
     * @return static
     */
    public function setPartyLegalEntityCompanyId(?string $partyLegalEntityCompanyId)
    {
        $this->partyLegalEntityCompanyId = $partyLegalEntityCompanyId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPartyLegalEntityCompanySchemeId(): ?string
    {
        return $this->partyLegalEntityCompanySchemeId;
    }

    /**
     * @param  string|null  $partyLegalEntityCompanySchemeId
     * @return static
     */
    public function setPartyLegalEntityCompanySchemeId(?string $partyLegalEntityCompanySchemeId)
    {
        $this->partyLegalEntityCompanySchemeId = $partyLegalEntityCompanySchemeId;
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
        if ($this->partyIdentificationId !== null) {
            $partyIdentificationAttributes = [];

            if (!empty($this->getPartyIdentificationSchemeId())) {
                $partyIdentificationAttributes['schemeID'] = $this->getPartyIdentificationSchemeId();
            }

            $writer->write([
                Schema::CAC . 'PartyIdentification' => [
                    [
                        'name'       => Schema::CBC . 'ID',
                        'value'      => $this->partyIdentificationId,
                        'attributes' => $partyIdentificationAttributes
                    ]
                ],
            ]);
        }
        if ($this->partyName !== null) {
            $writer->write([
                Schema::CAC . 'PartyName' => [
                    Schema::CBC . 'Name' => $this->partyName
                ],
            ]);
        }
        if ($this->partyLegalEntityCompanyId !== null) {
            $writer->write([
                Schema::CAC . 'PartyLegalEntity' => [
                    Schema::CBC . 'CompanyID' => $this->partyLegalEntityCompanyId
                ],
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

        $partyIdentification = ReaderHelper::getTag(Schema::CAC . 'PartyIdentification', $collection);
        $partyIdentificationId = ReaderHelper::getTag(Schema::CBC . 'ID', new ArrayCollection($partyIdentification['value'] ?? []));

        $partyName = ReaderHelper::getTag(Schema::CAC . 'PartyName', $collection);
        $partyNameName = ReaderHelper::getTag(Schema::CBC . 'Name', new ArrayCollection($partyName['value'] ?? []));

        $partyLegalEntity = ReaderHelper::getTag(Schema::CAC . 'PartyLegalEntity', $collection);
        $partyLegalEntityValue = $partyLegalEntity['value'] ?? null;
        $partyLegalEntityCompanyId = null;
        $partyLegalEntityCompanySchemeId = null;
        if ($partyLegalEntityValue instanceof LegalEntity) {
            $partyLegalEntityCompanyId = ['value' => $partyLegalEntityValue->getCompanyId()];
            $partyLegalEntityCompanySchemeId  = $partyLegalEntityValue->getCompanyIdSchemeId();
        }

        return (new static())
            ->setPartyIdentificationId($partyIdentificationId['value'] ?? null)
            ->setPartyIdentificationSchemeId($partyIdentificationId['attributes']['schemeID'] ?? null)
            ->setPartyName($partyNameName['value'] ?? null)
            ->setPartyLegalEntityCompanyId($partyLegalEntityCompanyId['value'] ?? null)
            ->setPartyLegalEntityCompanySchemeId($partyLegalEntityCompanySchemeId);
    }
}
