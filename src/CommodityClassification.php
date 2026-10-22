<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class CommodityClassification implements XmlSerializable, XmlDeserializable
{
    private ?string $itemClassificationCode = null;
    private ?string $itemClassificationListId = null;
    private ?string $itemClassificationListVersionId = null;

    /**
     * @return string|null
     */
    public function getItemClassificationCode(): ?string
    {
        return $this->itemClassificationCode;
    }

    /**
     * @param string|null $itemClassificationCode
     * @return static
     */
    public function setItemClassificationCode(?string $itemClassificationCode)
    {
        $this->itemClassificationCode = $itemClassificationCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getItemClassificationListId(): ?string
    {
        return $this->itemClassificationListId;
    }

    /**
     * @param ?string $itemClassificationListId
     * @return static
     */
    public function setItemClassificationListId(?string $itemClassificationListId)
    {
        $this->itemClassificationListId = $itemClassificationListId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getItemClassificationListVersionId(): ?string
    {
        return $this->itemClassificationListVersionId;
    }

    /**
     * @param ?string $itemClassificationListVersionId
     * @return static
     */
    public function setItemClassificationListVersionId(?string $itemClassificationListVersionId)
    {
        $this->itemClassificationListVersionId = $itemClassificationListVersionId;
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
        $attributes = [
            'listID' => $this->itemClassificationListId ?? '',
        ];

        if (!empty($this->itemClassificationListVersionId)) {
            $attributes['listVersionID'] = $this->itemClassificationListVersionId;
        }

        $writer->write([
            'name'       => Schema::CBC . 'ItemClassificationCode',
            'value'      => $this->itemClassificationCode ?? '',
            'attributes' => $attributes
        ]);
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     *
     * @param Reader $reader
     * @return CommodityClassification
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = mixedContent($reader);
        $collection = new ArrayCollection($mixedContent);

        // UBL CommodityClassification can contain both ItemClassificationCode and CommodityCode
        $classificationCode = ReaderHelper::getTag(Schema::CBC . 'ItemClassificationCode', $collection)
            ?? ReaderHelper::getTag(Schema::CBC . 'CommodityCode', $collection);

        if ($classificationCode === null) {
            return new static();
        }

        return (new static())
            ->setItemClassificationCode($classificationCode['value'] ?? null)
            ->setItemClassificationListId($classificationCode['attributes']['listID'] ?? null)
            ->setItemClassificationListVersionId($classificationCode['attributes']['listVersionID'] ?? null);
    }
}
