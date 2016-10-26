<?php
/**
 * Created by PhpStorm.
 * User: bram.vaneijk
 * Date: 13-10-2016
 * Time: 17:19
 */

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Party implements XmlSerializable{
    private $name;
    /**
     * @var Address
     */
    private $postalAddress;
    /**
     * @var Address
     */
    private $physicalLocation;
    /**
     * @var Contact
     */
    private $contact;

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Party
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Address
     */
    public function getPostalAddress() {
        return $this->postalAddress;
    }

    /**
     * @param Address $postalAddress
     * @return Party
     */
    public function setPostalAddress($postalAddress) {
        $this->postalAddress = $postalAddress;
        return $this;
    }

    /**
     * @return Address
     */
    public function getPhysicalLocation() {
        return $this->physicalLocation;
    }

    /**
     * @param Address $physicalLocation
     * @return Party
     */
    public function setPhysicalLocation($physicalLocation) {
        $this->physicalLocation = $physicalLocation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContact() {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     * @return Party
     */
    public function setContact($contact) {
        $this->contact = $contact;
        return $this;
    }

    function xmlSerialize(Writer $writer) {
        $writer->write([
            Schema::CAC.'PartyName' => [
                Schema::CBC.'Name' => $this->name
            ],
            Schema::CAC.'PostalAddress' => $this->postalAddress
        ]);

        if($this->physicalLocation){
            $writer->write([
               Schema::CAC.'PhysicalLocation' => [Schema::CAC.'Address' => $this->physicalLocation]
            ]);
        }

        if($this->contact){
            $writer->write([
                Schema::CAC.'Contact' => $this->contact
            ]);
        }

    }
}