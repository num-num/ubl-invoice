<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

use InvalidArgumentException;

class OrderLineReference implements XmlSerializable
{
    private $lineId;
    private $salesOrderLine;


    /**
     * The validate function that is called during xml writing to valid the data of the object.
     *
     * @return void
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
     */
    public function validate()
    {
    }

    /**
     * @return mixed
     */
    public function getLineId()
    {
        return $this->lineId;
    }

    /**
     * @param mixed $lineId
     */
    public function setLineId($lineId): void
    {
        $this->lineId = $lineId;
    }

    /**
     * @return mixed
     */
    public function getSalesOrderLine()
    {
        return $this->salesOrderLine;
    }

    /**
     * @param mixed $salesOrderLine
     */
    public function setSalesOrderLine($salesOrderLine): void
    {
        $this->salesOrderLine = $salesOrderLine;
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
                'name' => Schema::CBC . 'LineID',
                'value' => $this->lineId,
            ],
        ]);

        $writer->write([
            [
                'name' => Schema::CBC . 'SalesOrderLine',
                'value' => $this->salesOrderLine,
            ],
        ]);
    }
}
