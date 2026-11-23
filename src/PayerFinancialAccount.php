<?php

namespace NumNum\UBL;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

use function Sabre\Xml\Deserializer\keyValue;

class PayerFinancialAccount implements XmlSerializable, XmlDeserializable
{
    private ?string $id = null;

    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        return (new static())
            ->setId($keyValues[Schema::CBC . 'ID'] ?? null);
    }

    public function xmlSerialize(Writer $writer): void
    {
        if ($this->id !== null) {
            $writer->write([
                Schema::CBC . 'ID' => $this->id,
            ]);
        }
    }

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
}
