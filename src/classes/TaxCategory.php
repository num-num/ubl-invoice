<?php
/**
 * Created by PhpStorm.
 * User: bram.vaneijk
 * Date: 25-10-2016
 * Time: 15:40
 */

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class TaxCategory implements XmlSerializable {
    private $id;
    private $name;
    private $percent;
    private $taxScheme;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return TaxCategory
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return TaxCategory
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPercent() {
        return $this->percent;
    }

    /**
     * @param mixed $percent
     * @return TaxCategory
     */
    public function setPercent($percent) {
        $this->percent = $percent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxScheme() {
        return $this->taxScheme;
    }

    /**
     * @param mixed $taxScheme
     * @return TaxCategory
     */
    public function setTaxScheme($taxScheme) {
        $this->taxScheme = $taxScheme;
        return $this;
    }



    public function validate() {
        if ($this->id === null) {
            throw new \InvalidArgumentException('Missing taxcategory id');
        }

        if ($this->name === null) {
            throw new \InvalidArgumentException('Missing taxcategory name');
        }

        if ($this->percent === null) {
            throw new \InvalidArgumentException('Missing taxcategory percent');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    function xmlSerialize(Writer $writer) {
        $this->validate();

        $writer->write([
            Schema::CBC.'ID' => $this->id,
            Schema::CBC.'Name' => $this->name,
            Schema::CBC.'Percent' => $this->percent,
        ]);

        if($this->taxScheme != null){
            $writer->write([Schema::CAC.'TaxScheme' => $this->taxScheme]);
        }
    }
}