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

class SettlementPeriod implements XmlSerializable, XmlDeserializable
{
    private $startDate;
    private $endDate;

    /**
     * @return DateTime
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     * @return static
     */
    public function setStartDate(DateTime $startDate)
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
     * @return static
     */
    public function setEndDate(DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * The validate function that is called during xml writing to valid the data of the object.
     *
     * Volgens Peppol BIS 3.0 spec:
     * - StartDate (BT-73): 0..1 (optioneel)
     * - EndDate (BT-74): 0..1 (optioneel)
     * - BR-CO-19: "If Invoicing period is used, the start date or end date shall be filled, or both."
     *
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
     * @return void
     */
    public function validate()
    {
        // BR-CO-19: minstens startDate of endDate moet aanwezig zijn
        if ($this->startDate === null && $this->endDate === null) {
            throw new InvalidArgumentException('Missing startDate or endDate - at least one is required (BR-CO-19)');
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

        $data = [];

        // StartDate is optioneel (0..1)
        if ($this->startDate !== null) {
            $data[Schema::CBC . "StartDate"] = $this->startDate->format("Y-m-d");
        }

        // EndDate is optioneel (0..1)
        if ($this->endDate !== null) {
            $data[Schema::CBC . "EndDate"] = $this->endDate->format("Y-m-d");
        }

        $writer->write($data);

        // DurationMeasure alleen schrijven als beide datums aanwezig zijn
        if ($this->startDate !== null && $this->endDate !== null) {
            $writer->write([
                [
                    "name" => Schema::CBC . "DurationMeasure",
                    "value" => $this->endDate
                        ->diff($this->startDate)
                        ->format("%d"),
                    "attributes" => [
                        "unitCode" => "DAY",
                    ],
                ],
            ]);
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     *
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        $instance = new static();

        // StartDate is optioneel (0..1) volgens Peppol BIS 3.0 spec
        if (isset($keyValues[Schema::CBC . "StartDate"])) {
            $instance->setStartDate(
                Carbon::parse(
                    $keyValues[Schema::CBC . "StartDate"],
                )->toDateTime(),
            );
        }

        // EndDate is optioneel (0..1) volgens Peppol BIS 3.0 spec
        if (isset($keyValues[Schema::CBC . "EndDate"])) {
            $instance->setEndDate(
                Carbon::parse(
                    $keyValues[Schema::CBC . "EndDate"],
                )->toDateTime(),
            );
        }

        return $instance;
    }
}
