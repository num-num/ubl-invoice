<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;
use DateTime;
use InvalidArgumentException;

class InvoicePeriod implements XmlSerializable
{
    private $startDate;
    private $endDate;
    private $descriptionCode;

    /**
     * @return DateTime
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     * @return InvoicePeriod
     */
    public function setStartDate(?DateTime $startDate): InvoicePeriod
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     * @return InvoicePeriod
     */
    public function setEndDate(?DateTime $endDate): InvoicePeriod
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getDescriptionCode(): ?int
    {
        return $this->descriptionCode;
    }

    /**
     * @param Integer $descriptionCode
     * @return InvoicePeriod
     */
    public function setDescriptionCode(?int $descriptionCode): InvoicePeriod
    {
        $this->descriptionCode = $descriptionCode;
        return $this;
    }

    /**
     * The validate function that is called during xml writing to valid the data of the object.
     *
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
     * @return void
     */
    public function validate()
    {
        if ($this->descriptionCode === null && ($this->startDate === null && $this->endDate === null)) {
            throw new InvalidArgumentException('Missing startDate or endDate or descriptionCode');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        $this->validate();

        if ($this->startDate != null) {
            $writer->write([
                Schema::CBC . 'StartDate' => $this->startDate->format('Y-m-d'),
            ]);
        }
        if ($this->endDate != null) {
            $writer->write([
                Schema::CBC . 'EndDate' => $this->endDate->format('Y-m-d'),
            ]);
        }
        if ($this->descriptionCode !== null) {
            $writer->write([
                Schema::CBC . 'DescriptionCode' => $this->descriptionCode,
            ]);
        }
    }
}
