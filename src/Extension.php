<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use Doctrine\Common\Collections\ArrayCollection;

class Extension implements XmlSerializable, XmlDeserializable
{
    private $content;
    private array $attributes = [];

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function xmlSerialize(Writer $writer): void
    {
        $writer->write([
            [
                'name' => Schema::EXT . 'UBLExtension',
                'value' => [
                    Schema::EXT . 'ExtensionContent' => $this->content
                ],
                'attributes' => $this->attributes
            ]
        ]);
    }

    public static function xmlDeserialize(Reader $reader)
    {
        $attributes = $reader->parseAttributes();

        $collection = new ArrayCollection(mixedContent($reader));

        return (new static())
            ->setContent(ReaderHelper::getTagValue(Schema::EXT . 'ExtensionContent', $collection))
            ->setAttributes($attributes);
    }
}
