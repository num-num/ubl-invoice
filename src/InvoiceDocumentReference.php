<?php

namespace NumNum\UBL;

use DateTime;
use InvalidArgumentException;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class InvoiceDocumentReference implements XmlSerializable
{
    private $originalInvoiceId;
    private $issueDate;

    /**
     * Get the id of the invoice that is being credited
     * @return string
     */
    public function getOriginalInvoiceId(): ?string
    {
        return $this->originalInvoiceId;
    }

    /**
     * Set the id of the invoice that is being credited
     *
     * @return InvoiceDocumentReference
     */
    public function setOriginalInvoiceId(string $invoiceRef): InvoiceDocumentReference
    {
        $this->originalInvoiceId = $invoiceRef;
        return $this;
    }

    /**
     * Get the issue date of the original invoice that is being credited
     *
     * @return ?DateTime
     */
    public function getIssueDate(): ?DateTime
    {
        return $this->issueDate;
    }

    /**
     * Set the issue date of the original invoice that is being credited
     *
     * @return InvoiceDocumentReference
     */
    public function setIssueDate(DateTime $issueDate): InvoiceDocumentReference
    {
        $this->issueDate = $issueDate;
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
        if ($this->originalInvoiceId === null) {
            throw new InvalidArgumentException('Missing invoicedocumentreference originalinvoiceid');
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

        $writer->write([
            [
                'name' => Schema::CBC . 'ID',
                'value' => $this->originalInvoiceId
            ]
        ]);

        if ($this->issueDate != null) {
            $writer->write([
                [
                    'name' => Schema::CBC . 'IssueDate',
                    'value' => $this->issueDate->format('Y-m-d')
                ]
            ]);
        }
    }
}
