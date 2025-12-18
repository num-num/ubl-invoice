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
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = mixedContent($reader);
        $collection = new ArrayCollection($mixedContent);

        return (static::deserializedTag($mixedContent))
            ->setCreditNoteLines(ReaderHelper::getArrayValue(Schema::CAC . 'CreditNoteLine', $collection));
    }
}
