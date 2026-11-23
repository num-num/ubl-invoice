<?php

namespace NumNum\UBL;

use Carbon\Carbon;
use DateTime;
use InvalidArgumentException;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class InvoicePeriod implements XmlSerializable, XmlDeserializable
{
    private ?DateTime $startDate = null;
    private ?DateTime $endDate = null;
    private ?int $descriptionCode = null;

    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime|null $startDate
     * @return static
     */
    public function setStartDate(?DateTime $startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime|null $endDate
     * @return static
     */
    public function setEndDate(?DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDescriptionCode(): ?int
    {
        return $this->descriptionCode;
    }

    /**
     * @param int|null $descriptionCode
     * @return static
     */
    public function setDescriptionCode(?int $descriptionCode)
    {
        $this->descriptionCode = $descriptionCode;
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

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        $startDate = isset($keyValues[Schema::CBC . 'StartDate'])
            ? Carbon::parse($keyValues[Schema::CBC . 'StartDate'])->toDateTime()
            : null;

        $endDate = isset($keyValues[Schema::CBC . 'EndDate'])
            ? Carbon::parse($keyValues[Schema::CBC . 'EndDate'])->toDateTime()
            : null;

        $descriptionCode = isset($keyValues[Schema::CBC . 'DescriptionCode'])
            ? intval($keyValues[Schema::CBC . 'DescriptionCode'])
            : null;

        return (new static())
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setDescriptionCode($descriptionCode);
    }
}
