<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class AdditionalDocumentReference implements XmlSerializable, XmlDeserializable
{
    private ?string $id = null;
    private ?string $documentType = null;
    private $documentTypeCode = null;
    private ?string $documentDescription = null;
    private ?Attachment $attachment = null;

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

    /**
     * @return string|null
     */
    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    /**
     * @param string|null $documentType
     * @return static
     */
    public function setDocumentType(?string $documentType)
    {
        $this->documentType = $documentType;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getDocumentTypeCode()
    {
        return $this->documentTypeCode;
    }

    /**
     * @param int|string|null $documentTypeCode
     * @return static
     */
    public function setDocumentTypeCode($documentTypeCode)
    {
        $this->documentTypeCode = $documentTypeCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDocumentDescription(): ?string
    {
        return $this->documentDescription;
    }

    /**
     * @param string|null $documentDescription
     * @return static
     */
    public function setDocumentDescription(?string $documentDescription)
    {
        $this->documentDescription = $documentDescription;
        return $this;
    }

    /**
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return $this->attachment;
    }

    /**
     * @param Attachment|null $attachment
     * @return static
     */
    public function setAttachment(?Attachment $attachment)
    {
        $this->attachment = $attachment;
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
        $writer->write([ Schema::CBC . 'ID' => $this->id ]);

        if ($this->documentTypeCode !== null) {
            $writer->write([
                Schema::CBC . 'DocumentTypeCode' => $this->documentTypeCode
            ]);
        }

        if ($this->documentType !== null) {
            $writer->write([
                Schema::CBC . 'DocumentType' => $this->documentType
            ]);
        }

        if ($this->documentDescription !== null) {
            $writer->write([
                Schema::CBC . 'DocumentDescription' => $this->documentDescription
            ]);
        }

        if ($this->attachment !== null) {
            $writer->write([
              Schema::CAC . 'Attachment' => $this->attachment
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
            ->setId($keyValues[Schema::CBC . 'ID'] ?? null)
            ->setDocumentType($keyValues[Schema::CBC . 'DocumentType'] ?? null)
            ->setDocumentTypeCode($keyValues[Schema::CBC . 'DocumentTypeCode'] ?? null)
            ->setDocumentDescription($keyValues[Schema::CBC . 'DocumentDescription'] ?? null)
            ->setAttachment($keyValues[Schema::CAC . 'Attachment'] ?? null);
    }
}
