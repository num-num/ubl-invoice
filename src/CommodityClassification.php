<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class CommodityClassification implements XmlSerializable
{
    private $itemClassificationCode;
    private $itemClassificationListId;
    private $itemClassificationListVersionId;

    /**
     * @return string
     */
    public function getItemClassificationCode(): ?string
    {
        return $this->itemClassificationCode;
    }

    /**
     * @param string $itemClassificationCode
     * @return CommodityClassification
     */
    public function setItemClassificationCode(?string $itemClassificationCode): CommodityClassification
    {
        $this->itemClassificationCode = $itemClassificationCode;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getItemClassificationListId(): ?string
    {
        return $this->itemClassificationListId;
    }

    /**
     * @param ?string $itemClassificationListId
     * @return CommodityClassification
     */
    public function setItemClassificationListId(?string $itemClassificationListId): CommodityClassification
    {
        $this->itemClassificationListId = $itemClassificationListId;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getItemClassificationListVersionId(): ?string
    {
        return $this->itemClassificationListVersionId;
    }

    /**
     * @param ?string $itemClassificationListVersionId
     * @return CommodityClassification
     */
    public function setItemClassificationListVersionId(?string $itemClassificationListVersionId): CommodityClassification
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
}
