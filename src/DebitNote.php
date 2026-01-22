<?php

namespace NumNum\UBL;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

use function Sabre\Xml\Deserializer\mixedContent;

class DebitNote extends Invoice implements XmlSerializable, XmlDeserializable
{
    public $xmlTagName = 'DebitNote';
    protected $invoiceTypeCode = InvoiceTypeCode::DEBIT_NOTE;

    /**
     * @return DebitNoteLine[]
     */
    public function getDebitNoteLines(): ?array
    {
        return $this->invoiceLines;
    }

    /**
     * @param DebitNoteLine[] $debitNoteLines
     * @return static
     */
    public function setDebitNoteLines(array $debitNoteLines)
    {
        $this->invoiceLines = $debitNoteLines;
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
            ->setDebitNoteLines(ReaderHelper::getArrayValue(Schema::CAC . 'DebitNoteLine', $collection));
    }
}
