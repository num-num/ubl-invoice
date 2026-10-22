<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class AccountingParty implements XmlSerializable, XmlDeserializable
{
    private ?string $supplierAssignedAccountID = null;
    private ?Party $party = null;
    private ?Contact $accountingContact = null;

    /**
     * @return string|null
     */
    public function getSupplierAssignedAccountId(): ?string
    {
        return $this->supplierAssignedAccountID;
    }

    /**
     * @param string|null $supplierAssignedAccountID
     * @return static
     */
    public function setSupplierAssignedAccountId(?string $supplierAssignedAccountID)
    {
        $this->supplierAssignedAccountID = $supplierAssignedAccountID;
        return $this;
    }

    /**
     * @return Party|null
     */
    public function getParty(): ?Party
    {
        return $this->party;
    }

    /**
     * @param Party|null $party
     * @return static
     */
    public function setParty(?Party $party)
    {
        $this->party = $party;
        return $this;
    }

    /**
     * @return Contact|null
     */
    public function getAccountingContact(): ?Contact
    {
        return $this->accountingContact;
    }

    /**
     * @param Contact|null $accountingContact
     * @return AccountingParty
     */
    public function setAccountingContact(?Contact $accountingContact): AccountingParty
    {
        $this->accountingContact = $accountingContact;
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
        if (!empty($this->supplierAssignedAccountID)) {
            $writer->write([
                Schema::CBC . 'SupplierAssignedAccountID' => $this->supplierAssignedAccountID,
            ]);
        }

        if (!empty($this->party)) {
            $writer->write([
                Schema::CAC . 'Party' => $this->party,
            ]);
        }

        if (!empty($this->accountingContact)) {
            $writer->write([
                Schema::CAC . 'AccountingContact' => $this->accountingContact,
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
            ->setParty($keyValues[Schema::CAC . 'Party'] ?? null)
            ->setSupplierAssignedAccountId($keyValues[Schema::CBC . 'SupplierAssignedAccountID'] ?? null)
            ->setAccountingContact($keyValues[Schema::CBC . 'AccountingContact'] ?? null);
    }
}
