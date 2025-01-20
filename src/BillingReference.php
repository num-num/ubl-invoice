<?php

namespace NumNum\UBL;

use InvalidArgumentException;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class BillingReference implements XmlSerializable, XmlDeserializable
{
    private $invoiceDocumentReference;

    /**
     *
     * @return ?InvoiceDocumentReference
     */
    public function getInvoiceDocumentReference(): ?InvoiceDocumentReference
    {
        return $this->invoiceDocumentReference;
    }

    /**
     *
     * @return static
     */
    public function setInvoiceDocumentReference($invoiceDocumentReference)
    {
        $this->invoiceDocumentReference = $invoiceDocumentReference;
        return $this;
    }

    /**
     * The validate function that is called during xml writing to valid the data of the object.
     *
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
     * @return void
     */
    public function validate()
    {
        if ($this->invoiceDocumentReference === null) {
            throw new InvalidArgumentException('Missing billingreference invoicedocumentreference');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        $this->validate();

        $writer->write([Schema::CAC . 'InvoiceDocumentReference' => $this->invoiceDocumentReference]);
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $xml
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = keyValue($reader);

        return (new static())
            ->setInvoiceDocumentReference($mixedContent[Schema::CAC . 'InvoiceDocumentReference'] ?? null)
        ;
    }
}
