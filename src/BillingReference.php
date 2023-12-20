<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

use InvalidArgumentException;

class BillingReference implements XmlSerializable
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
     * @return BillingReference
     */
    public function setInvoiceDocumentReference($invoiceDocumentReference): BillingReference
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
}
