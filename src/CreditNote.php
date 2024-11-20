<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class CreditNote extends Invoice implements XmlSerializable
{
    public $xmlTagName = 'CreditNote';
    protected $invoiceTypeCode = InvoiceTypeCode::CREDIT_NOTE;
    protected $billingReference;

    /**
     * @return CreditNoteLine[]
     */
    public function getCreditNoteLines(): ?array
    {
        return $this->invoiceLines;
    }

    /**
     * @param CreditNoteLine[] $creditNoteLines
     * @return CreditNote
     */
    public function setCreditNoteLines(array $creditNoteLines): CreditNote
    {
        $this->invoiceLines = $creditNoteLines;
        return $this;
    }

    /**
     * Get the reference to the invoice that is being credited
     *
     * @return ?BillingReference
     */
    public function getBillingReference(): ?BillingReference
    {
        return $this->billingReference;
    }

    /**
     * Set the reference to the invoice that is being credited
     *
     * @return CreditNote
     */
    public function setBillingReference($billingReference): CreditNote
    {
        $this->billingReference = $billingReference;
        return $this;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        Invoice::xmlSerialize($writer);

        if (!empty($this->billingReference)) {
            $writer->write([
                Schema::CAC . 'BillingReference' => $this->billingReference
            ]);
        }
    }
}
