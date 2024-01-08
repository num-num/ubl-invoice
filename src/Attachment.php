<?php

namespace NumNum\UBL;

use Exception;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

use InvalidArgumentException;

class Attachment implements XmlSerializable
{
    private $filePath;
    private $externalReference;
    private $fileContent;
	private $fileName;
    private $mimeType;

    /**
     * @throws Exception exception when the mime type cannot be determined
     * @return string
     */
    public function getFileMimeType(): string
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
     * @return Attachment
     */
    public function setFilePath(string $filePath): Attachment
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
     * @return Attachment
     */
    public function setExternalReference(string $externalReference): Attachment
    {
        $this->externalReference = $externalReference;
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
     * @return Attachment
     */
	public function setFileName(string $fileName): Attachment
	{
		$this->fileName = $fileName;
		return $this;
	}

    /**
     * @return string
     */
	public function getFileContent(): string
	{
		return $this->fileContent;
	}

    /**
     * @param string $fileContent
     * @return Attachment
     */
	public function setFileContent(string $fileContent): Attachment
	{
		$this->fileContent = $fileContent;
		return $this;
	}

    /**
     * @return string
     */
	public function getMimeType(): string
	{
		return $this->mimeType;
	}

    /**
     * @param string $mimeType
     * @return Attachment
     */
	public function setMimeType(string $mimeType): Attachment
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
        if ($this->filePath === null && $this->externalReference === null && $this->fileContent === null) {
            throw new InvalidArgumentException('Attachment must have a filePath, an ExternalReference, or a fileContent');
        }

        if ($this->fileContent !== null && $this->mimeType === null) {
            throw new InvalidArgumentException('Using fileContent, you need to define the mime type');
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

        if ($this->filePath || $this->fileContent) {
            if ($this->filePath) {
                $fileContents = base64_encode(file_get_contents($this->filePath));
                $mimeType = $this->getFileMimeType();
            } else {
                $fileContents = $this->fileContent;
                $mimeType = $this->mimeType;
            }

            $writer->write([
                'name' => Schema::CBC . 'EmbeddedDocumentBinaryObject',
                'value' => $fileContents,
                'attributes' => [
                    'mimeCode' => $mimeType,
                    'filename' => basename($this->filePath)
                ]
            ]);
        }

        if ($this->externalReference) {
            $writer->writeElement(
                Schema::CAC . 'ExternalReference',
                [ Schema::CBC . 'URI' => $this->externalReference ]
            );
        }
    }
}
