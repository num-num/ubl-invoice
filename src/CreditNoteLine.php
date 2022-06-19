<?php

namespace NumNum\UBL;

class CreditNoteLine extends InvoiceLine
{
    public $xmlTagName = 'CreditNoteLine';
    protected $isCreditNoteLine = true;

    /**
     * @return float
     */
    public function getCreditedQuantity(): ?float
    {
        return $this->invoicedQuantity;
    }

    /**
     * @param ?float $creditedQuantity
     * @return CreditNoteLine
     */
    public function setCreditedQuantity(?float $creditedQuantity): CreditNoteLine
    {
        $this->invoicedQuantity = $creditedQuantity;
        return $this;
    }
}
