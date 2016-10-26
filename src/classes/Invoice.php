<?php
/**
 * Created by PhpStorm.
 * User: bram.vaneijk
 * Date: 13-10-2016
 * Time: 16:29
 */

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Invoice implements XmlSerializable{
    private $UBLVersionID = '2.0';

    /**
     * @var int
     */
    private $id;
    /**
     * @var bool
     */
    private $copyIndicator = false;

    /**
     * @var \DateTime
     */
    private $issueDate;
    /**
     * @var string
     */

    private $invoiceTypeCode;
    /**
     * @var Party
     */
    private $accountingSupplierParty;
    /**
     * @var Party
     */
    private $accountingCustomerParty;
    /**
     * @var TaxTotal[]
     */
    private $taxTotal;
    /**
     * @var LegalMonetaryTotal
     */
    private $legalMonetaryTotal;
    /**
     * @var InvoiceLine[]
     */
    private $invoiceLines;


    function validate(){
        if($this->id === null){
            throw new \InvalidArgumentException('Missing invoice id');
        }

        if($this->id === null){
            throw new \InvalidArgumentException('Missing invoice id');
        }

        if(!$this->issueDate instanceof \DateTime){
            throw new \InvalidArgumentException('Invalid invoice issueDate');
        }

        if($this->invoiceTypeCode === null){
            throw new \InvalidArgumentException('Missing invoice invoiceTypeCode');
        }

        if($this->accountingSupplierParty === null){
            throw new \InvalidArgumentException('Missing invoice accountingSupplierParty');
        }

        if($this->accountingCustomerParty === null){
            throw new \InvalidArgumentException('Missing invoice accountingCustomerParty');
        }

        if($this->invoiceLines === null){
            throw new \InvalidArgumentException('Missing invoice lines');
        }

        if($this->legalMonetaryTotal === null){
            throw new \InvalidArgumentException('Missing invoice LegalMonetaryTotal');
        }

        if($this->taxTotal === null){
            throw new \InvalidArgumentException('Missing invoice taxTotal');
        }
    }

    function xmlSerialize(Writer $writer) {
        $cbc = '{urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2}';
        $cac = '{urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2}';

        $this->validate();

        $writer->write([
            $cbc.'UBLVersionID' => $this->UBLVersionID,
            $cbc.'ID' => $this->id,
            $cbc.'CopyIndicator' => $this->copyIndicator ? 'true' : 'false',
            $cbc.'IssueDate' => $this->issueDate->format('Y-m-d'),
            $cbc.'InvoiceTypeCode' => $this->invoiceTypeCode,
            $cac.'AccountingSupplierParty' => [$cac."Party" => $this->accountingSupplierParty],
            $cac.'AccountingCustomerParty' => [$cac."Party" => $this->accountingSupplierParty],
            $cac.'LegalMonetaryTotal' => $this->legalMonetaryTotal
        ]);

        foreach($this->taxTotal as $taxTotal){
            $writer->write([
                Schema::CAC.'TaxTotal' => $taxTotal
            ]);
        }

        foreach($this->invoiceLines as $invoiceLine){
            $writer->write([
               Schema::CAC.'InvoiceLine' => $invoiceLine
            ]);
        }

    }

    /**
     * @return int
     */
    public function getUBLVersionID() {
        return $this->UBLVersionID;
    }

    /**
     * @param int $UBLVersionID
     */
    public function setUBLVersionID($UBLVersionID) {
        $this->UBLVersionID = $UBLVersionID;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return boolean
     */
    public function isCopyIndicator() {
        return $this->copyIndicator;
    }

    /**
     * @param boolean $copyIndicator
     */
    public function setCopyIndicator($copyIndicator) {
        $this->copyIndicator = $copyIndicator;
    }

    /**
     * @return \DateTime
     */
    public function getIssueDate() {
        return $this->issueDate;
    }

    /**
     * @param \DateTime $issueDate
     */
    public function setIssueDate(\DateTime $issueDate) {
        $this->issueDate = $issueDate;
    }

    /**
     * @return string
     */
    public function getInvoiceTypeCode() {
        return $this->invoiceTypeCode;
    }

    /**
     * @param string $invoiceTypeCode
     */
    public function setInvoiceTypeCode($invoiceTypeCode) {
        $this->invoiceTypeCode = $invoiceTypeCode;
    }

    /**
     * @return mixed
     */
    public function getAccountingSupplierParty() {
        return $this->accountingSupplierParty;
    }

    /**
     * @param mixed $accountingSupplierParty
     */
    public function setAccountingSupplierParty($accountingSupplierParty) {
        $this->accountingSupplierParty = $accountingSupplierParty;
    }

    /**
     * @return mixed
     */
    public function getAccountingCustomerParty() {
        return $this->accountingCustomerParty;
    }

    /**
     * @param mixed $accountingCustomerParty
     */
    public function setAccountingCustomerParty($accountingCustomerParty) {
        $this->accountingCustomerParty = $accountingCustomerParty;
    }

    /**
     * @return mixed
     */
    public function getTaxTotal() {
        return $this->taxTotal;
    }

    /**
     * @param mixed $taxTotal
     */
    public function setTaxTotal($taxTotal) {
        $this->taxTotal = $taxTotal;
    }

    /**
     * @return mixed
     */
    public function getLegalMonetaryTotal() {
        return $this->legalMonetaryTotal;
    }

    /**
     * @param mixed $legalMonetaryTotal
     */
    public function setLegalMonetaryTotal($legalMonetaryTotal) {
        $this->legalMonetaryTotal = $legalMonetaryTotal;
    }

    /**
     * @return mixed
     */
    public function getInvoiceLines() {
        return $this->invoiceLines;
    }

    /**
     * @param mixed $InvoiceLines
     */
    public function setInvoiceLines($InvoiceLines) {
        $this->invoiceLines = $InvoiceLines;
    }




}