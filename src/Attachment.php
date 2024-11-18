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
    private $fileStream;
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

    public function getFileStream(): ?string
    {
        return $this->fileStream;
    }

    /**
     * @param string $fileStream
     * @param string $fileName
     * @return Attachment
     */
    public function setFileStream(string $fileStream, string $fileName): Attachment
    {
        $this->fileStream = $fileStream;
        $this->fileName = $fileName;
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
		return $this->fileStream;
	}

    /**
     * @param string $fileContent
     * @return Attachment
     */
	public function setFileContent(string $fileContent): Attachment
	{
		$this->fileStream = $fileContent;
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
        if ($this->filePath === null && $this->externalReference === null && $this->fileStream === null) {
            throw new InvalidArgumentException('Attachment must have a filePath, an externalReference, or a fileContent');
        }

        if ($this->fileStream !== null && $this->mimeType === null) {
            throw new InvalidArgumentException('Using fileStream, you need to define a mimeType by also using setFileMimeType');
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

        if ($this->filePath || $this->fileStream) {
            if ($this->filePath) {
                $fileContents = base64_encode(file_get_contents($this->filePath));
                $mimeType = $this->getFileMimeType();
            } else {
                $fileContents = $this->fileStream;
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

        if ($this->fileStream) {
            $data = [
                'name'       => Schema::CBC . 'EmbeddedDocumentBinaryObject',
                'value'      => $this->fileStream,
                'attributes' => [
                    'mimeCode' => 'application/pdf',
                    'filename' => $this->fileName,
                ],
            ];

            $writer->write($data);
        }
    }
}
