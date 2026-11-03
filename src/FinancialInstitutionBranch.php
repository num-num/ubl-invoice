<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class FinancialInstitutionBranch implements XmlSerializable, XmlDeserializable
{
    private ?string $id = null;

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

    public function xmlSerialize(Writer $writer): void
    {
        if ($this->id !== null) {
            $writer->write([
                Schema::CBC . 'ID' => $this->id
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

        return (new static())
            ->setId($keyValues[Schema::CBC . 'ID'] ?? null);
    }
}
