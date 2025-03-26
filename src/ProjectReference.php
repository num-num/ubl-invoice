<?php

namespace NumNum\UBL;

use DateTime;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class ProjectReference implements XmlSerializable
{
    private $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ProjectReference
     */
    public function setId(string $id): ProjectReference
    {
        $this->id = $id;
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
        if ($this->id !== null) {
            $writer->write([Schema::CBC . 'ID' => $this->id]);
        }
    }
}
