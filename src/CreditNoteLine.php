<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

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

        $creditedQuantityTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'CreditedQuantity'))[0] ?? null;

        return (static::deserializedTag($mixedContent))
            ->setCreditedQuantity(isset($creditedQuantityTag) ? floatval($creditedQuantityTag['value']) : null)
        ;
    }
}
