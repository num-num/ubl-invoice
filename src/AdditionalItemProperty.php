<?php

namespace NumNum\UBL;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

use function Sabre\Xml\Deserializer\keyValue;

/**
 * Additional Item Property
 * A group of business terms providing information about properties of the goods and services invoiced.
 *
 * @see https://docs.peppol.eu/poacc/billing/3.0/syntax/ubl-invoice/cac-InvoiceLine/cac-Item/cac-AdditionalItemProperty/
 */
class AdditionalItemProperty implements XmlSerializable, XmlDeserializable
{
    private $name;
    private $value;

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
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return static
     */
    public function setValue(?string $value)
    {
        $this->value = $value;
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
            $writer->write([Schema::CBC . 'Name' => $this->name]);
        }
        if ($this->value !== null) {
            $writer->write([Schema::CBC . 'Value' => $this->value]);
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     *
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = keyValue($reader);

        return (new static())
            ->setName($mixedContent[Schema::CBC . 'Name'] ?? null)
            ->setValue($mixedContent[Schema::CBC . 'Value'] ?? null);
    }
}
