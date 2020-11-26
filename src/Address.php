<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Address implements XmlSerializable
{
    private $streetName;
    private $additionalStreetName;
    private $buildingNumber;
    private $cityName;
    private $postalZone;
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
     * @return Address
     */
    public function setStreetName(?string $streetName): Address
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
     * @return Address
     */
    public function setAdditionalStreetName(?string $additionalStreetName): Address
    {
        $this->additionalStreetName = $additionalStreetName;
        return $this;
    }

    /**
    /**
     * @return string
     */
    public function getBuildingNumber(): ?string
    {
        return $this->buildingNumber;
    }

    /**
     * @param string $buildingNumber
     * @return Address
     */
    public function setBuildingNumber(?string $buildingNumber): Address
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
     * @return Address
     */
    public function setCityName(?string $cityName): Address
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
     * @return Address
     */
    public function setPostalZone(?string $postalZone): Address
    {
        $this->postalZone = $postalZone;
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
     * @return Address
     */
    public function setCountry(Country $country): Address
    {
        $this->country = $country;
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
        if ($this->country !== null) {
            $writer->write([
                Schema::CAC . 'Country' => $this->country,
            ]);
        }
    }
}
