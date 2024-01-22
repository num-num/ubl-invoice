<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use InvalidArgumentException;
use Sabre\Xml\XmlSerializable;

/**
 * @see https://docs.peppol.eu/poacc/billing/3.0/syntax/ubl-invoice/cac-InvoiceLine/cac-OrderLineReference/
 */
class OrderLineReference implements XmlSerializable
{
    private $lineId;

    /**
     * @return string
     */
    public function getLineId(): ?string
    {
        return $this->lineId;
    }

    /**
     * @param string $lineId
     * @return OrderLineReference
     */
    public function setLineId(?string $lineId): OrderLineReference
    {
        $this->lineId = $lineId;
        return $this;
    }

    /**
     * The validate function that is called during xml writing to valid the data of the object.
     *
     * @return void
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
     */
    public function validate()
    {
        if ($this->lineId === null) {
            throw new InvalidArgumentException('Missing OrderLineReference LineID');
        }
    }

    public function xmlSerialize(Writer $writer): void
    {
        $this->validate();

        $writer->write([
            Schema::CBC . 'LineID' => $this->lineId
        ]);
    }
}
