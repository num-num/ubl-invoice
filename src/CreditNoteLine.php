<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;

class CreditNoteLine extends InvoiceLine
{
    public $xmlTagName = 'CreditNoteLine';

    /**
     * @return float
     */
    public function getCreditedQuantity(): ?float
    {
        return $this->invoicedQuantity;
    }

    /**
     * @param ?float $creditedQuantity
     * @return static
     */
    public function setCreditedQuantity(?float $creditedQuantity)
    {
        $this->invoicedQuantity = $creditedQuantity;
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

        $creditedQuantityTag = ReaderHelper::getTag(Schema::CBC . 'CreditedQuantity', $collection);

        return (static::deserializedTag($mixedContent))
            ->setCreditedQuantity(isset($creditedQuantityTag) ? floatval($creditedQuantityTag['value']) : null)
        ;
    }
}
