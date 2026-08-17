<?php

namespace NumNum\UBL;

use Carbon\Carbon;
use DateTime;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class OrderReference implements XmlSerializable, XmlDeserializable
{
    private ?string $id = null;
    private ?string $salesOrderId;
    private ?DateTime $issueDate = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return static
     */
    public function setId(?string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalesOrderId(): ?string
    {
        return $this->salesOrderId;
    }

    /**
     * @return DateTime|null
     */
    public function getIssueDate(): ?DateTime
    {
        return $this->issueDate;
    }

    /**
     * @param DateTime|null $issueDate
     * @return static
     */
    public function setIssueDate(?DateTime $issueDate)
    {
        $this->issueDate = $issueDate;
        return $this;
    }

    /**
     * @param string|null $salesOrderId
     * @return static
     */
    public function setSalesOrderId(?string $salesOrderId)
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
    public function xmlSerialize(Writer $writer): void
    {
        if ($this->id !== null) {
            $writer->write([Schema::CBC . 'ID' => $this->id]);
        }
        if ($this->salesOrderId !== null) {
            $writer->write([Schema::CBC . 'SalesOrderID' => $this->salesOrderId]);
        }
        if ($this->issueDate !== null) {
            $writer->write([
                Schema::CBC . 'IssueDate' => $this->issueDate->format('Y-m-d'),
            ]);
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        $issueDate = isset($keyValues[Schema::CBC . 'IssueDate'])
            ? Carbon::parse($keyValues[Schema::CBC . 'IssueDate'])->toDateTime()
            : null;

        return (new static())
            ->setId($keyValues[Schema::CBC . 'ID'] ?? null)
            ->setIssueDate($issueDate)
            ->setSalesOrderId($keyValues[Schema::CBC . 'SalesOrderID'] ?? null);
    }
}
