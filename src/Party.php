<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

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
     * @param int|string $schemeID See list at https://docs.peppol.eu/poacc/billing/3.0/codelist/eas/
     * @return static
     */
    public function setEndpointID($endpointID, $schemeID)
    {
        $this->endpointID = $endpointID;
        $this->endpointID_schemeID = $schemeID;
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
        $keyValues = keyValue($reader);

        // Add more complex logic due to the fact that Party + child Elements are nested
        $childPartyName = $keyValues[Schema::CAC.'PartyName'] ?? null;
        $childPartyNameName = array_values(array_filter($childPartyName ?? [], fn ($element) => $element['name'] == Schema::CBC . 'Name'))[0] ?? null;

        $childPhysicalLocation = $keyValues[Schema::CAC.'PhysicalLocation'] ?? null;
        $childPhysicalLocationAddress = array_values(array_filter($childPhysicalLocation ?? [], fn ($element) => $element['name'] == Schema::CAC . 'Address'))[0] ?? null;

        return (new static())
            ->setName($childPartyNameName['value'] ?? null)
            ->setPostalAddress($keyValues[Schema::CAC . 'PostalAddress'] ?? null)
            ->setPhysicalLocation($childPhysicalLocationAddress['value'] ?? null)
            ->setPartyTaxScheme($childPartyTaxScheme['value'] ?? null)
            ->setLegalEntity($keyValues[Schema::CAC.'PartyLegalEntity'] ?? null)
            ->setContact($keyValues[Schema::CAC.'Contact'] ?? null)
        // @todo PartyIdentificationId
        //     ->setPartyIdentificationId($keyValues[Schema::CAC . 'PartyIdentification'][0][Schema::CBC . 'ID'] ?? null)
        //     ->setPartyIdentificationSchemeId($keyValues[Schema::CAC . 'PartyIdentification'][0][Schema::CBC . 'ID']['attributes']['schemeID'] ?? null)
        //     ->setPartyIdentificationSchemeName($keyValues[Schema::CAC . 'PartyIdentification'][0][Schema::CBC . 'ID']['attributes']['schemeName'] ?? null)
        ;
    }
}
