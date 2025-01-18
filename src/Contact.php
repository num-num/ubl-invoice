<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class Contact implements XmlSerializable, XmlDeserializable
{
    private $name;
    private $telephone;
    private $telefax;
    private $electronicMail;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     * @return static
     */
    public function setTelephone(?string $telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelefax(): ?string
    {
        return $this->telefax;
    }

    /**
     * @param string $telefax
     * @return static
     */
    public function setTelefax(?string $telefax)
    {
        $this->telefax = $telefax;
        return $this;
    }

    /**
     * @return string
     */
    public function getElectronicMail(): ?string
    {
        return $this->electronicMail;
    }

    /**
     * @param string $electronicMail
     * @return static
     */
    public function setElectronicMail(?string $electronicMail)
    {
        $this->electronicMail = $electronicMail;
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
        if ($this->name !== null) {
            $writer->write([
                Schema::CBC . 'Name' => $this->name
            ]);
        }

        if ($this->telephone !== null) {
            $writer->write([
                Schema::CBC . 'Telephone' => $this->telephone
            ]);
        }

        if ($this->telefax !== null) {
            $writer->write([
                Schema::CBC . 'Telefax' => $this->telefax
            ]);
        }

        if ($this->electronicMail !== null) {
            $writer->write([
                Schema::CBC . 'ElectronicMail' => $this->electronicMail
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

        return (new static())
            ->setName($keyValues[Schema::CBC . 'Name'] ?? null)
            ->setTelephone($keyValues[Schema::CBC . 'Telephone'] ?? null)
            ->setTelefax($keyValues[Schema::CBC . 'Telefax'] ?? null)
            ->setElectronicMail($keyValues[Schema::CBC . 'ElectronicMail'] ?? null);
        ;
    }
}
