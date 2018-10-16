<?php
/**
 * @author bert@builtinbruges.com
 * @date 08-10-2018
 * @time 22:40
 */

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Attachment implements XmlSerializable{
    private $filePath;

    /**
     * @return String
     */
    public function getFileMimeType() {
        return mime_content_type($this->filePath);
    }

    /**
     * @return String
     */
    public function getFilePath() {
        return $this->filePath;
    }

    /**
     * @param String $filePath
     * @return AdditionalDocumentReference
     */
    public function setFilePath($filePath) {
        $this->filePath = $filePath;
        return $this;
    }

    function validate() {
        if ($this->filePath === null) {
            throw new \InvalidArgumentException('Missing filePath');
        }
        if (file_exists($this->filePath) === false) {
            throw new \InvalidArgumentException('Attachment at filePath does not exist');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    function xmlSerialize(Writer $writer) {
        // TODO: Implement xmlSerialize() method.
        $cbc = '{urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2}';
        $cac = '{urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2}';

        $fileContents = base64_encode(file_get_contents($this->filePath));
        $mimeType = $this->getFileMimeType();

        $this->validate();

        $writer->write([
            'name' => Schema::CBC . 'EmbeddedDocumentBinaryObject',
            'value' => $fileContents,
            'attributes' => [
                'mimeCode' => $mimeType
            ]
        ]);
    }
}
