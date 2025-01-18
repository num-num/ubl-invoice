<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class Country implements XmlSerializable, XmlDeserializable
{
    private $identificationCode;
    private $listId;

    /**
     * @return mixed
     */
    public function getIdentificationCode(): ?string
    {
        return $this->identificationCode;
    }

    /**
     * @param mixed $identificationCode
     * @return static
     */
    public function setIdentificationCode(?string $identificationCode)
    {
        $this->identificationCode = $identificationCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getListId(): ?string
    {
        return $this->listId;
    }

    /**
     * @param mixed $listId
     * @return static
     */
    public function setListId(?string $listId)
    {
        $this->listId = $listId;
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
        $attributes = [];

        if (!empty($this->listId)) {
            $attributes['listID'] = 'ISO3166-1:Alpha2';
        }

        $writer->write([
            'name'       => Schema::CBC . 'IdentificationCode',
            'value'      => $this->identificationCode,
            'attributes' => $attributes
        ]);
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $xml
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        return (new static())
            ->setIdentificationCode($keyValues[Schema::CBC . 'IdentificationCode'])
            ->setListId($keyValues[Schema::CBC . 'IdentificationCode']['attributes']['listID'] ?? null)
        ;
    }
}
