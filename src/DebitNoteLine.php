<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;

class DebitNoteLine extends InvoiceLine
{
    public $xmlTagName = 'DebitNoteLine';

    /**
     * @return float
     */
    public function getDebitedQuantity(): ?float
    {
        return $this->invoicedQuantity;
    }

    /**
     * @param ?float $debitedQuantity
     * @return static
     */
    public function setDebitedQuantity(?float $debitedQuantity)
    {
        $this->invoicedQuantity = $debitedQuantity;
        return $this;
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $xml
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = mixedContent($reader);
        $collection = new ArrayCollection($mixedContent);

        $debitedQuantityTag = ReaderHelper::getTag(Schema::CBC . 'DebitedQuantity', $collection);

        return (static::deserializedTag($mixedContent))
            ->setDebitedQuantity(isset($debitedQuantityTag) ? floatval($debitedQuantityTag['value']) : null)
        ;
    }
}
