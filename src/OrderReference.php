<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class OrderReference implements XmlSerializable
{
    private $id;
    private $salesOrderId;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return OrderReference
     */
    public function setId(string $id): OrderReference
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSalesOrderId(): string
    {
        return $this->salesOrderId;
    }

    /**
     * @param string $salesOrderId
     * @return OrderReference
     */
    public function setSalesOrderId(string $salesOrderId): OrderReference
    {
        $this->salesOrderId = $salesOrderId;
        return $this;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer)
    {
        if ($this->id !== null) {
            $writer->write([ Schema::CBC . 'ID' => $this->id ]);
        }
        if ($this->salesOrderId !== null) {
            $writer->write([ Schema::CBC . 'SalesOrderID' => $this->salesOrderId ]);
        }
    }
}
