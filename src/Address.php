<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class Address implements XmlSerializable, XmlDeserializable
{
    private $streetName;
    private $additionalStreetName;
    private $buildingNumber;
    private $cityName;
    private $postalZone;
    private $countrySubentity;
    private $country;

    /**
     * @return string
     */
    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    /**
     * @param string $streetName
     * @return static
     */
    public function setStreetName(?string $streetName)
    {
        $this->streetName = $streetName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalStreetName(): ?string
    {
        return $this->additionalStreetName;
    }

    /**
     * @param string $additionalStreetName
     * @return static
     */
    public function setAdditionalStreetName(?string $additionalStreetName)
    {
        $this->additionalStreetName = $additionalStreetName;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuildingNumber(): ?string
    {
        return $this->buildingNumber;
    }

    /**
     * @param string $buildingNumber
     * @return static
     */
    public function setBuildingNumber(?string $buildingNumber)
    {
        $this->buildingNumber = $buildingNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    /**
     * @param string $cityName
     * @return static
     */
    public function setCityName(?string $cityName)
    {
        $this->cityName = $cityName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalZone(): ?string
    {
        return $this->postalZone;
    }

    /**
     * @param string $postalZone
     * @return static
     */
    public function setPostalZone(?string $postalZone)
    {
        $this->postalZone = $postalZone;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountrySubentity(): ?string
    {
        return $this->countrySubentity;
    }

    /**
     * @param string $subentity
     * @return static
     */
    public function setCountrySubentity(?string $countrySubentity)
    {
        $this->countrySubentity = $countrySubentity;
        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * @param Country $country
     * @return Country
     */
    public function setCountry(?Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return static
     */
    public function xmlSerialize(Writer $writer): void
    {
        if ($this->streetName !== null) {
            $writer->write([
                Schema::CBC . 'StreetName' => $this->streetName
            ]);
        }
        if ($this->additionalStreetName !== null) {
            $writer->write([
                Schema::CBC . 'AdditionalStreetName' => $this->additionalStreetName
            ]);
        }
        if ($this->buildingNumber !== null) {
            $writer->write([
                Schema::CBC . 'BuildingNumber' => $this->buildingNumber
            ]);
        }
        if ($this->cityName !== null) {
            $writer->write([
                Schema::CBC . 'CityName' => $this->cityName,
            ]);
        }
        if ($this->postalZone !== null) {
            $writer->write([
                Schema::CBC . 'PostalZone' => $this->postalZone,
            ]);
        }
        if ($this->countrySubentity !== null) {
            $writer->write([
                Schema::CBC . 'CountrySubentity' => $this->countrySubentity,
            ]);
        }
        if ($this->country !== null) {
            $writer->write([
                Schema::CAC . 'Country' => $this->country,
            ]);
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $xml
     * @return PaymentTerms
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        return (new static())
            ->setStreetName($keyValues[Schema::CBC . 'StreetName'] ?? null)
            ->setAdditionalStreetName($keyValues[Schema::CBC . 'AdditionalStreetName'] ?? null)
            ->setBuildingNumber($keyValues[Schema::CBC . 'BuildingNumber'] ?? null)
            ->setCityName($keyValues[Schema::CBC . 'CityName'] ?? null)
            ->setPostalZone($keyValues[Schema::CBC . 'PostalZone'] ?? null)
            ->setCountrySubentity($keyValues[Schema::CBC . 'CountrySubentity'] ?? null)
            ->setCountry($keyValues[Schema::CAC . 'Country'] ?? null);
    }
}
