<?php

namespace NumNum\UBL;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

use function Sabre\Xml\Deserializer\mixedContent;

class CreditNote extends Invoice implements XmlSerializable, XmlDeserializable
{
    public $xmlTagName = 'CreditNote';
    protected $invoiceTypeCode = InvoiceTypeCode::CREDIT_NOTE;

    /**
     * @return CreditNoteLine[]
     */
    public function getCreditNoteLines(): ?array
    {
        return $this->invoiceLines;
    }

    /**
     * @param CreditNoteLine[] $creditNoteLines
     * @return static
     */
    public function setCreditNoteLines(array $creditNoteLines)
    {
        $this->invoiceLines = $creditNoteLines;
        return $this;
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     *
     * Overrides parent to read CreditNoteTypeCode instead of InvoiceTypeCode.
     * CreditNotes use CreditNoteTypeCode (e.g., 261 for self-billing credit note)
     * while Invoices use InvoiceTypeCode (e.g., 389 for self-billing invoice).
     *
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = mixedContent($reader);
        $collection = new ArrayCollection($mixedContent);

        $instance = static::deserializedTag($mixedContent)
            ->setCreditNoteLines(ReaderHelper::getArrayValue(Schema::CAC . 'CreditNoteLine', $collection));

        // Read CreditNoteTypeCode (CreditNotes use this instead of InvoiceTypeCode)
        $creditNoteTypeCode = ReaderHelper::getTagValue(
            Schema::CBC . 'CreditNoteTypeCode',
            $collection
        );

        if ($creditNoteTypeCode !== null) {
            $instance->setInvoiceTypeCode((int) $creditNoteTypeCode);
        }

        return $instance;
    }
}
