<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;
use function Sabre\Xml\Deserializer\mixedContent;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class Party implements XmlSerializable, XmlDeserializable
{
    private $name;
    private $partyIdentificationId;
    private $partyIdentificationSchemeId;
    private $partyIdentificationSchemeName;
    private $postalAddress;
    private $physicalLocation;
    private $contact;
    private $partyTaxScheme;
    private $legalEntity;
    private $endpointID;
    private $endpointID_schemeID;

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
     * @return string
     */
    public function getPartyIdentificationId(): ?string
    {
        return $this->partyIdentificationId;
    }

    /**
     * @param string $partyIdentificationId
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
     * @param string $partyIdentificationSchemeId
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
    public function getPartyIdentificationSchemeName(): ?string
    {
        return $this->partyIdentificationSchemeName;
    }

    /**
     * @param string $partyIdentificationSchemeName
     * @return static
     */
    public function setPartyIdentificationSchemeName(?string $partyIdentificationSchemeName)
    {
        $this->partyIdentificationSchemeName = $partyIdentificationSchemeName;
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
     * @return static
     */
    public function setPostalAddress(?Address $postalAddress)
    {
        $this->postalAddress = $postalAddress;
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
     * @return static
     */
    public function setLegalEntity(?LegalEntity $legalEntity)
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
     * @return static
     */
    public function setPhysicalLocation(?Address $physicalLocation)
    {
        $this->physicalLocation = $physicalLocation;
        return $this;
    }

    /**
     * @return staticTaxScheme
     */
    public function getPartyTaxScheme(): ?PartyTaxScheme
    {
        return $this->partyTaxScheme;
    }

    /**
     * @param PartyTaxScheme $partyTaxScheme
     * @return static
     */
    public function setPartyTaxScheme(?PartyTaxScheme $partyTaxScheme)
    {
        $this->partyTaxScheme = $partyTaxScheme;
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
     * @return static
     */
    public function setContact(?Contact $contact)
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @param $endpointID
     * @param string|int $schemeID See list at https://docs.peppol.eu/poacc/billing/3.0/codelist/eas/ and use \NumNum\UBL\EASCode
     * @return static
     */
    public function setEndpointId($endpointID, $schemeID)
    {
        $this->endpointID = $endpointID;
        $this->endpointID_schemeID = $schemeID;
        return $this;
    }

    public function getEndpointId(): ?string
    {
        return $this->endpointID;
    }

    public function getEndpointIDSchemeId(): ?string
    {
        return $this->endpointID_schemeID;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        if ($this->endpointID !== null && $this->endpointID_schemeID !== null) {
            $writer->write([
                [
                    'name'       => Schema::CBC . 'EndpointID',
                    'value'      => $this->endpointID,
                    'attributes' => [
                        'schemeID' => is_numeric($this->endpointID_schemeID)
                            ? sprintf('%04d', +$this->endpointID_schemeID)
                            : $this->endpointID_schemeID
                    ]
                ]
            ]);
        }
        if ($this->partyIdentificationId !== null) {
            $partyIdentificationAttributes = [];

            if (!empty($this->getPartyIdentificationSchemeId())) {
                $partyIdentificationAttributes['schemeID'] = $this->getPartyIdentificationSchemeId();
            }

            if (!empty($this->getPartyIdentificationSchemeName())) {
                $partyIdentificationAttributes['schemeName'] = $this->getPartyIdentificationSchemeName();
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
        if ($this->name !== null) {
            $writer->write([
                Schema::CAC . 'PartyName' => [
                    Schema::CBC . 'Name' => $this->name
                ]
            ]);
        }
        if ($this->postalAddress !== null) {
            $writer->write([
                Schema::CAC . 'PostalAddress' => $this->postalAddress
            ]);
        }
        if ($this->physicalLocation !== null) {
            $writer->write([
               Schema::CAC . 'PhysicalLocation' => [Schema::CAC . 'Address' => $this->physicalLocation]
            ]);
        }
        if ($this->partyTaxScheme !== null) {
            $writer->write([
                Schema::CAC . 'PartyTaxScheme' => $this->partyTaxScheme
            ]);
        }
        if ($this->legalEntity !== null) {
            $writer->write([
                Schema::CAC . 'PartyLegalEntity' => $this->legalEntity
            ]);
        }
        if ($this->contact !== null) {
            $writer->write([
                Schema::CAC . 'Contact' => $this->contact
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

        $partyName = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'PartyName'))[0] ?? null;
        $partyNameName = array_values(array_filter($partyName['value'] ?? [], fn ($element) => $element['name'] == Schema::CBC . 'Name'))[0] ?? null;

        $endpointId = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'EndpointID'))[0] ?? null;
        $postalAddress = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'PostalAddress'))[0] ?? null;
        $physicalLocation = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'PhysicalLocation'))[0] ?? null;
        $physicalLocationAddress = array_values(array_filter($physicalLocation['value'] ?? [], fn ($element) => $element['name'] === Schema::CAC . 'Address'))[0] ?? null;
        $partyTaxScheme = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'PartyTaxScheme'))[0] ?? null;
        $partyLegalEntity = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'PartyLegalEntity'))[0] ?? null;
        $partyContact = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'Contact'))[0] ?? null;

        $partyIdentification = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'PartyIdentification'))[0] ?? null;
        $partyIdentificationId = array_values(array_filter($partyIdentification['value'] ?? [], fn ($element) => $element['name'] === Schema::CBC . 'ID'))[0] ?? null;

        return (new static())
            ->setName($partyNameName['value'] ?? null)
            ->setPostalAddress($postalAddress['value'] ?? null)
            ->setPhysicalLocation($physicalLocationAddress['value'] ?? null)
            ->setPartyTaxScheme($partyTaxScheme['value'] ?? null)
            ->setLegalEntity($partyLegalEntity['value'] ?? null)
            ->setContact($partyContact['value'] ?? null)
            ->setEndpointId($endpointId['value'] ?? null, $endpointId['attributes']['schemeID'] ?? null)
            ->setPartyIdentificationId($partyIdentificationId['value'] ?? null)
            ->setPartyIdentificationSchemeId($partyIdentificationId['attributes']['schemeID'] ?? null)
            ->setPartyIdentificationSchemeName($partyIdentificationId['attributes']['schemeName'] ?? null)
        ;
    }
}
