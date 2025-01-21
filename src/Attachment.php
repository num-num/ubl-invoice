<?php

namespace NumNum\UBL;

use Exception;
use InvalidArgumentException;

use function Sabre\Xml\Deserializer\mixedContent;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class Attachment implements XmlSerializable, XmlDeserializable
{
    private $filePath;
    private $externalReference;
    private $base64Content;
    private $fileName;
    private $mimeType;

    /**
     * @throws Exception exception when the mime type cannot be determined
     * @return string
     */
    public function getFilePathMimeType(): string
    {
        if (($mime_type = mime_content_type($this->filePath)) !== false) {
            return $mime_type;
        }

        throw new Exception('Could not determine mime_type of '.$this->filePath);
    }

    /**
     * @return string
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     * @return static
     */
    public function setFilePath(string $filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalReference(): ?string
    {
        return $this->externalReference;
    }

    /**
     * @param string $externalReference
     * @return static
     */
    public function setExternalReference(string $externalReference)
    {
        $this->externalReference = $externalReference;
        return $this;
    }

    public function getBase64Content(): ?string
    {
        return $this->base64Content;
    }

    /**
     * @param string $base64Content Base64 encoded base64Content
     * @param string $fileName
     * @return static
     */
    public function setBase64Content(string $base64Content, string $fileName, ?string $mimeType)
    {
        $this->base64Content = $base64Content;
        $this->fileName = $fileName;
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return static
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param ?string $mimeType
     * @return static
     */
    public function setMimeType(?string $mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * The validate function that is called during xml writing to valid the data of the object.
     *
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
     * @return void
     */
    public function validate()
    {
        if ($this->filePath === null && $this->externalReference === null && $this->base64Content === null) {
            throw new InvalidArgumentException('Attachment must have a filePath, an externalReference, or a fileContent');
        }

        if ($this->base64Content !== null && $this->mimeType === null) {
            throw new InvalidArgumentException('Using base64Content, you need to define a mimeType by also using setFileMimeType');
        }

        if ($this->filePath !== null && !file_exists($this->filePath)) {
            throw new InvalidArgumentException('Attachment at filePath does not exist');
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

        if (!empty($this->filePath)) {
            $fileContents = base64_encode(file_get_contents($this->filePath));
            $fileName = basename($this->filePath);
            $mimeType = $this->getFilePathMimeType();
        } else {
            $fileContents = $this->base64Content;
            $fileName = $this->fileName;
            $mimeType = $this->mimeType;
        }

        $writer->write([
            'name'       => Schema::CBC . 'EmbeddedDocumentBinaryObject',
            'value'      => $fileContents,
            'attributes' => [
                'mimeCode' => $mimeType,
                'filename' => $fileName,
            ]
        ]);

        if ($this->externalReference) {
            $writer->writeElement(
                Schema::CAC . 'ExternalReference',
                [ Schema::CBC . 'URI' => $this->externalReference ]
            );
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $xml
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = mixedContent($reader);

        $embeddedDocumentBinaryObject = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'EmbeddedDocumentBinaryObject'))[0] ?? null;

        return (new static())
            ->setBase64Content(
                $embeddedDocumentBinaryObject['value'] ?? null,
                $embeddedDocumentBinaryObject['attributes']['filename'] ?? null,
                $embeddedDocumentBinaryObject['attributes']['mimeCode'] ?? null
            )
        ;
    }
}
