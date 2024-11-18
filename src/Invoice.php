<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

use DateTime;
use InvalidArgumentException;

class Invoice implements XmlSerializable
{
    public $xmlTagName = 'Invoice';
    private $UBLVersionID = '2.1';
    private $customizationID = '1.0';
    private $profileID;
    private $id;
    private $copyIndicator;
    private $issueDate;
    protected $invoiceTypeCode = InvoiceTypeCode::INVOICE;
    private $note;
    private $taxPointDate;
    private $dueDate;
    private $paymentTerms;
    private $accountingSupplierParty;
    private $accountingCustomerParty;
    private $payeeParty;
    private $supplierAssignedAccountID;
    private $paymentMeans;
    private $taxTotal;
    private $legalMonetaryTotal;
    /** @var InvoiceLine[] $invoiceLines */
    protected $invoiceLines;
    private $allowanceCharges;
    private $additionalDocumentReferences = [];
    private $documentCurrencyCode = 'EUR';
    private $buyerReference;
    private $accountingCostCode;
    private $invoicePeriod;
    private $delivery;
    private $orderReference;
    private $contractDocumentReference;

    /**
     * @return string
     */
    public function getUBLVersionID(): ?string
    {
        return $this->UBLVersionID;
    }

    /**
     * @param string $UBLVersionID
     * eg. '2.0', '2.1', '2.2', ...
     * @return Invoice
     */
    public function setUBLVersionID(?string $UBLVersionID): Invoice
    {
        $this->UBLVersionID = $UBLVersionID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Invoice
     */
    public function setId(?string $id): Invoice
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $customizationID
     * @return Invoice
     */
    public function setCustomizationID(?string $customizationID): Invoice
    {
        $this->customizationID = $customizationID;
        return $this;
    }

    /**
     * @param mixed $profileID
     * @return Invoice
     */
    public function setProfileID(?string $profileID): Invoice
    {
        $this->profileID = $profileID;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCopyIndicator(): bool
    {
        return $this->copyIndicator;
    }

    /**
     * @param bool $copyIndicator
     * @return Invoice
     */
    public function setCopyIndicator(bool $copyIndicator): Invoice
    {
        $this->copyIndicator = $copyIndicator;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getIssueDate(): ?DateTime
    {
        return $this->issueDate;
    }

    /**
     * @param DateTime $issueDate
     * @return Invoice
     */
    public function setIssueDate(DateTime $issueDate): Invoice
    {
        $this->issueDate = $issueDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDueDate(): ?DateTime
    {
        return $this->dueDate;
    }

    /**
     * @param DateTime $dueDate
     * @return Invoice
     */
    public function setDueDate(DateTime $dueDate): Invoice
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * @param mixed $currencyCode
     * @return Invoice
     */
    public function setDocumentCurrencyCode(string $currencyCode = 'EUR'): Invoice
    {
        $this->documentCurrencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceTypeCode(): ?string
    {
        return $this->invoiceTypeCode;
    }

    /**
     * @param string $invoiceTypeCode
     * See also: src/InvoiceTypeCode.php
     * @return Invoice
     */
    public function setInvoiceTypeCode(string $invoiceTypeCode): Invoice
    {
        $this->invoiceTypeCode = $invoiceTypeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     * @return Invoice
     */
    public function setNote(string $note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTaxPointDate(): ?DateTime
    {
        return $this->taxPointDate;
    }

    /**
     * @param DateTime $taxPointDate
     * @return Invoice
     */
    public function setTaxPointDate(DateTime $taxPointDate): Invoice
    {
        $this->taxPointDate = $taxPointDate;
        return $this;
    }

    /**
     * @return PaymentTerms
     */
    public function getPaymentTerms(): ?PaymentTerms
    {
        return $this->paymentTerms;
    }

    /**
     * @param PaymentTerms $paymentTerms
     * @return Invoice
     */
    public function setPaymentTerms(PaymentTerms $paymentTerms): Invoice
    {
        $this->paymentTerms = $paymentTerms;
        return $this;
    }

    /**
     * @return Party
     */
    public function getAccountingSupplierParty(): ?Party
    {
        return $this->accountingSupplierParty;
    }

    /**
     * @param Party $accountingSupplierParty
     * @return Invoice
     */
    public function setAccountingSupplierParty(Party $accountingSupplierParty): Invoice
    {
        $this->accountingSupplierParty = $accountingSupplierParty;
        return $this;
    }

    /**
     * @return Party
     */
    public function getSupplierAssignedAccountID(): ?string
    {
        return $this->supplierAssignedAccountID;
    }

    /**
     * @param string $supplierAssignedAccountID
     * @return Invoice
     */
    public function setSupplierAssignedAccountID(string $supplierAssignedAccountID): Invoice
    {
        $this->supplierAssignedAccountID = $supplierAssignedAccountID;
        return $this;
    }

    /**
     * @return Party
     */
    public function getAccountingCustomerParty(): ?Party
    {
        return $this->accountingCustomerParty;
    }

    /**
     * @param Party $accountingCustomerParty
     * @return Invoice
     */
    public function setAccountingCustomerParty(Party $accountingCustomerParty): Invoice
    {
        $this->accountingCustomerParty = $accountingCustomerParty;
        return $this;
    }

    /**
     * @return Party
     */
    public function getPayeeParty(): ?Party
    {
        return $this->payeeParty;
    }

    /**
     * @param Party $payeeParty
     * @return Invoice
     */
    public function setPayeeParty(Party $payeeParty): Invoice
    {
        $this->payeeParty = $payeeParty;
        return $this;
    }

    /**
     * @return PaymentMeans
     */
    public function getPaymentMeans(): ?PaymentMeans
    {
        return $this->paymentMeans;
    }

    /**
     * @param PaymentMeans $paymentMeans
     * @return Invoice
     */
    public function setPaymentMeans(PaymentMeans $paymentMeans): Invoice
    {
        $this->paymentMeans = $paymentMeans;
        return $this;
    }

    /**
     * @return TaxTotal
     */
    public function getTaxTotal(): ?TaxTotal
    {
        return $this->taxTotal;
    }

    /**
     * @param TaxTotal $taxTotal
     * @return Invoice
     */
    public function setTaxTotal(TaxTotal $taxTotal): Invoice
    {
        $this->taxTotal = $taxTotal;
        return $this;
    }

    /**
     * @return LegalMonetaryTotal
     */
    public function getLegalMonetaryTotal(): ?LegalMonetaryTotal
    {
        return $this->legalMonetaryTotal;
    }

    /**
     * @param LegalMonetaryTotal $legalMonetaryTotal
     * @return Invoice
     */
    public function setLegalMonetaryTotal(LegalMonetaryTotal $legalMonetaryTotal): Invoice
    {
        $this->legalMonetaryTotal = $legalMonetaryTotal;
        return $this;
    }

    /**
     * @return InvoiceLine[]
     */
    public function getInvoiceLines(): ?array
    {
        return $this->invoiceLines;
    }

    /**
     * @param InvoiceLine[] $invoiceLines
     * @return Invoice
     */
    public function setInvoiceLines(array $invoiceLines): Invoice
    {
        $this->invoiceLines = $invoiceLines;
        return $this;
    }

    /**
     * @return AllowanceCharge[]
     */
    public function getAllowanceCharges(): ?array
    {
        return $this->allowanceCharges;
    }

    /**
     * @param AllowanceCharge[] $allowanceCharges
     * @return Invoice
     */
    public function setAllowanceCharges(array $allowanceCharges): Invoice
    {
        $this->allowanceCharges = $allowanceCharges;
        return $this;
    }

    /**
     * @return AdditionalDocumentReference
     * @deprecated Deprecated since v1.16 - Replace implementation with setAdditionalDocumentReference or addAdditionalDocumentReference to add/set a single AdditionalDocumentReference
     */
    public function getAdditionalDocumentReference(): ?AdditionalDocumentReference
    {
        return $this->additionalDocumentReferences[0] ?? null;
    }

    /**
     * @return array<AdditionalDocumentReference>
     */
    public function getAdditionalDocumentReferences(): array
    {
        return $this->additionalDocumentReferences ?? [];
    }

    /**
     * @param AdditionalDocumentReference $additionalDocumentReference
     * @return Invoice
     */
    public function setAdditionalDocumentReference(AdditionalDocumentReference $additionalDocumentReference): Invoice
    {
        $this->additionalDocumentReferences = [$additionalDocumentReference];
        return $this;
    }

    /**
     * @param AdditionalDocumentReference $additionalDocumentReference
     * @return Invoice
     */
    public function setAdditionalDocumentReferences(array $additionalDocumentReference): Invoice
    {
        $this->additionalDocumentReferences = $additionalDocumentReference;
        return $this;
    }

    /**
     * @param AdditionalDocumentReference $additionalDocumentReference
     * @return Invoice
     */
    public function addAdditionalDocumentReference(AdditionalDocumentReference $additionalDocumentReference): Invoice
    {
        $this->additionalDocumentReferences[] = $additionalDocumentReference;
        return $this;
    }

    /**
     * @param string $buyerReference
     * @return Invoice
     */
    public function setBuyerReference(string $buyerReference): Invoice
    {
        $this->buyerReference = $buyerReference;
        return $this;
    }

      /**
     * @return string buyerReference
     */
    public function getBuyerReference(): ?string
    {
        return $this->buyerReference;
    }

    /**
     * @return mixed
     */
    public function getAccountingCostCode(): ?string
    {
        return $this->accountingCostCode;
    }

    /**
     * @param mixed $accountingCostCode
     * @return Invoice
     */
    public function setAccountingCostCode(string $accountingCostCode): Invoice
    {
        $this->accountingCostCode = $accountingCostCode;
        return $this;
    }

    /**
     * @return InvoicePeriod
     */
    public function getInvoicePeriod(): ?InvoicePeriod
    {
        return $this->invoicePeriod;
    }

    /**
     * @param InvoicePeriod $invoicePeriod
     * @return Invoice
     */
    public function setInvoicePeriod(InvoicePeriod $invoicePeriod): Invoice
    {
        $this->invoicePeriod = $invoicePeriod;
        return $this;
    }

    /**
     * @return Delivery
     */
    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    /**
     * @param Delivery $delivery
     * @return Invoice
     */
    public function setDelivery(Delivery $delivery): Invoice
    {
        $this->delivery = $delivery;
        return $this;
    }

    /**
     * @return OrderReference
     */
    public function getOrderReference(): ?OrderReference
    {
        return $this->orderReference;
    }

    /**
     * @param OrderReference $orderReference
     * @return OrderReference
     */
    public function setOrderReference(OrderReference $orderReference): Invoice
    {
        $this->orderReference = $orderReference;
        return $this;
    }

    /**
     * @return ContractDocumentReference
     */
    public function getContractDocumentReference(): ?ContractDocumentReference
    {
        return $this->contractDocumentReference;
    }

    /**
     * @param string $ContractDocumentReference
     * @return Invoice
     */
    public function setContractDocumentReference(ContractDocumentReference $contractDocumentReference): Invoice
    {
        $this->contractDocumentReference = $contractDocumentReference;
        return $this;
    }

    /**
     * The validate function that is called during xml writing to valid the data of the object.
     *
     * @return void
     * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
     */
    public function validate()
    {
        if ($this->id === null) {
            throw new InvalidArgumentException('Missing invoice id');
        }

        if (!$this->issueDate instanceof DateTime) {
            throw new InvalidArgumentException('Invalid invoice issueDate');
        }

        if ($this->invoiceTypeCode === null) {
            throw new InvalidArgumentException('Missing invoice invoiceTypeCode');
        }

        if ($this->accountingSupplierParty === null) {
            throw new InvalidArgumentException('Missing invoice accountingSupplierParty');
        }

        if ($this->accountingCustomerParty === null) {
            throw new InvalidArgumentException('Missing invoice accountingCustomerParty');
        }

        if ($this->invoiceLines === null) {
            throw new InvalidArgumentException('Missing invoice lines');
        }

        if ($this->legalMonetaryTotal === null) {
            throw new InvalidArgumentException('Missing invoice LegalMonetaryTotal');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        $this->validate();

        $writer->write([
            Schema::CBC . 'UBLVersionID' => $this->UBLVersionID,
            Schema::CBC . 'CustomizationID' => $this->customizationID,
        ]);

        if ($this->profileID !== null) {
            $writer->write([
                Schema::CBC . 'ProfileID' => $this->profileID
            ]);
        }

        $writer->write([
            Schema::CBC . 'ID' => $this->id
        ]);

        if ($this->copyIndicator !== null) {
            $writer->write([
                Schema::CBC . 'CopyIndicator' => $this->copyIndicator ? 'true' : 'false'
            ]);
        }

        $writer->write([
            Schema::CBC . 'IssueDate' => $this->issueDate->format('Y-m-d'),
        ]);

        if ($this->dueDate !== null && $this->xmlTagName === 'Invoice') {
            $writer->write([
                Schema::CBC . 'DueDate' => $this->dueDate->format('Y-m-d')
            ]);
        }

        if ($this->invoiceTypeCode !== null) {
            $writer->write([
                Schema::CBC . $this->xmlTagName . 'TypeCode' => $this->invoiceTypeCode
            ]);
        }

        if ($this->note !== null) {
            $writer->write([
                Schema::CBC . 'Note' => $this->note
            ]);
        }

        if ($this->taxPointDate !== null) {
            $writer->write([
                Schema::CBC . 'TaxPointDate' => $this->taxPointDate->format('Y-m-d')
            ]);
        }

        $writer->write([
            Schema::CBC . 'DocumentCurrencyCode' => $this->documentCurrencyCode,
        ]);

        if ($this->accountingCostCode !== null) {
            $writer->write([
                Schema::CBC . 'AccountingCostCode' => $this->accountingCostCode
            ]);
        }

        if ($this->buyerReference != null) {
            $writer->write([
                Schema::CBC . 'BuyerReference' => $this->buyerReference
            ]);
        }

        if ($this->invoicePeriod != null) {
            $writer->write([
                Schema::CAC . 'InvoicePeriod' => $this->invoicePeriod
            ]);
        }

        if ($this->orderReference != null) {
            $writer->write([
                Schema::CAC . 'OrderReference' => $this->orderReference
            ]);
        }

        if ($this->contractDocumentReference !== null) {
            $writer->write([
                Schema::CAC . 'ContractDocumentReference' => $this->contractDocumentReference,
            ]);
        }

        if (!empty($this->additionalDocumentReferences)) {
            foreach ($this->additionalDocumentReferences as $additionalDocumentReference) {
                $writer->write([
                    Schema::CAC . 'AdditionalDocumentReference' => $additionalDocumentReference
                ]);
            }
        }

        if ($this->supplierAssignedAccountID !== null) {
            $customerParty = [
                Schema::CBC . 'SupplierAssignedAccountID' => $this->supplierAssignedAccountID,
                Schema::CAC . "Party" => $this->accountingCustomerParty
            ];
        } else {
            $customerParty = [
                Schema::CAC . "Party" => $this->accountingCustomerParty
            ];
        }

        $writer->write([
            Schema::CAC . 'AccountingSupplierParty' => [Schema::CAC . "Party" => $this->accountingSupplierParty],
            Schema::CAC . 'AccountingCustomerParty' => $customerParty,
        ]);

        if ($this->payeeParty != null) {
            $writer->write([
                Schema::CAC . 'PayeeParty' => $this->payeeParty
            ]);
        }

        if ($this->delivery != null) {
            $writer->write([
                Schema::CAC . 'Delivery' => $this->delivery
            ]);
        }

        if ($this->paymentMeans !== null) {
            $writer->write([
                Schema::CAC . 'PaymentMeans' => $this->paymentMeans
            ]);
        }

        if ($this->paymentTerms !== null) {
            $writer->write([
                Schema::CAC . 'PaymentTerms' => $this->paymentTerms
            ]);
        }

        if ($this->allowanceCharges !== null) {
            foreach ($this->allowanceCharges as $allowanceCharge) {
                $writer->write([
                    Schema::CAC . 'AllowanceCharge' => $allowanceCharge
                ]);
            }
        }

        if ($this->taxTotal !== null) {
            $writer->write([
                Schema::CAC . 'TaxTotal' => $this->taxTotal
            ]);
        }

        $writer->write([
            Schema::CAC . 'LegalMonetaryTotal' => $this->legalMonetaryTotal
        ]);

        foreach ($this->invoiceLines as $invoiceLine) {
            $writer->write([
                Schema::CAC . $invoiceLine->xmlTagName => $invoiceLine
            ]);
        }
    }
}
