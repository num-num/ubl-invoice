<?php

namespace NumNum\UBL;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

use function Sabre\Xml\Deserializer\keyValue;

class AddressLine implements XmlSerializable, XmlDeserializable
{
    private $line;

    /**
     * @return string|null
     */
    public function getLine(): ?string
    {
        return $this->line;
    }

    /**
     * @param string|null $line
     * @return static
     */
    public function setLine(?string $line)
    {
        $this->line = $line;
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
        if ($this->line !== null) {
            $writer->write([
                Schema::CBC . 'Line' => $this->line
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

        return (new static())
            ->setLine($keyValues[Schema::CBC . 'Line'] ?? null);
    }
}
