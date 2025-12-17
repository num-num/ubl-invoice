<?php

namespace NumNum\UBL;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

use function Sabre\Xml\Deserializer\keyValue;

/**
 * Despatch Advice Reference
 * A reference to a despatch advice associated with this document.
 *
 * @see https://docs.peppol.eu/poacc/billing/3.0/syntax/ubl-invoice/cac-DespatchDocumentReference/
 */
class DespatchDocumentReference implements XmlSerializable, XmlDeserializable
{
    private $id;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     */
    public function setId(?string $id)
    {
        $this->id = $id;
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
        if ($this->id !== null) {
            $writer->write([Schema::CBC . 'ID' => $this->id]);
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
            ->setId($mixedContent[Schema::CBC . 'ID'] ?? null);
    }
}
