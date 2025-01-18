<?php

namespace NumNum\UBL;

use Carbon\Carbon;
use DateTime;
use InvalidArgumentException;

use function Sabre\Xml\Deserializer\mixedContent;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class Invoice implements XmlSerializable, XmlDeserializable
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
    /** @var PaymentMeans[] $paymentMeans */
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
    private $billingReference;
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
     *                             eg. '2.0', '2.1', '2.2', ...
     * @return static
     */
    public function setUBLVersionID(?string $UBLVersionID)
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
     * @return static
     */
    public function setId(?string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $customizationID
     * @return static
     */
    public function setCustomizationID(?string $customizationID)
    {
        $this->customizationID = $customizationID;
        return $this;
    }

    /**
     * @param mixed $profileID
     * @return static
     */
    public function setProfileID(?string $profileID)
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
     * @return static
     */
    public function setCopyIndicator(bool $copyIndicator)
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
     * @return static
     */
    public function setIssueDate(?DateTime $issueDate)
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
     * @return static
     */
    public function setDueDate(DateTime $dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * @param mixed $currencyCode
     * @return static
     */
    public function setDocumentCurrencyCode(?string $currencyCode = 'EUR')
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
     *                                See also: src/InvoiceTypeCode.php
     * @return static
     */
    public function setInvoiceTypeCode(string $invoiceTypeCode)
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
     * @param ?string $note
     * @return static
     */
    public function setNote(?string $note)
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
     * @return static
     */
    public function setTaxPointDate(DateTime $taxPointDate)
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
     * @param ?PaymentTerms $paymentTerms
     * @return static
     */
    public function setPaymentTerms(?PaymentTerms $paymentTerms)
    {
        $this->paymentTerms = $paymentTerms;
        return $this;
    }

    /**
     * @return AccountingParty
     */
    public function getAccountingSupplierParty(): ?AccountingParty
    {
        return $this->accountingSupplierParty;
    }

    /**
     * @param AccountingParty $accountingSupplierParty
     * @return static
     */
    public function setAccountingSupplierParty(AccountingParty $accountingSupplierParty)
    {
        $this->accountingSupplierParty = $accountingSupplierParty;
        return $this;
    }

    /**
     * @return AccountingParty
     */
    public function getAccountingCustomerParty(): ?AccountingParty
    {
        return $this->accountingCustomerParty;
    }

    /**
     * @param AccountingParty $accountingCustomerParty
     * @return static
     */
    public function setAccountingCustomerParty(AccountingParty $accountingCustomerParty)
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
     * @return static
     */
    public function setPayeeParty(?Party $payeeParty)
    {
        $this->payeeParty = $payeeParty;
        return $this;
    }

    /**
     * @return PaymentMeans[]
     */
    public function getPaymentMeans(): ?array
    {
        return $this->paymentMeans;
    }

    /**
     * @param PaymentMeans[] $paymentMeans
     * @return static
     */
    public function setPaymentMeans(array $paymentMeans)
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
     * @return static
     */
    public function setTaxTotal(TaxTotal $taxTotal)
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
     * @return static
     */
    public function setLegalMonetaryTotal(LegalMonetaryTotal $legalMonetaryTotal)
    {
        $this->legalMonetaryTotal = $legalMonetaryTotal;
        return $this;
    }

    /**
     * @return staticLine[]
     */
    public function getInvoiceLines(): ?array
    {
        return $this->invoiceLines;
    }

    /**
     * @param InvoiceLine[] $invoiceLines
     * @return static
     */
    public function setInvoiceLines(array $invoiceLines)
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
     * @return static
     */
    public function setAllowanceCharges(array $allowanceCharges)
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
     * @return static
     */
    public function setAdditionalDocumentReference(AdditionalDocumentReference $additionalDocumentReference)
    {
        $this->additionalDocumentReferences = [$additionalDocumentReference];
        return $this;
    }

    /**
     * @param AdditionalDocumentReference $additionalDocumentReference
     * @return static
     */
    public function setAdditionalDocumentReferences(array $additionalDocumentReference)
    {
        $this->additionalDocumentReferences = $additionalDocumentReference;
        return $this;
    }

    /**
     * @param AdditionalDocumentReference $additionalDocumentReference
     * @return static
     */
    public function addAdditionalDocumentReference(AdditionalDocumentReference $additionalDocumentReference)
    {
        $this->additionalDocumentReferences[] = $additionalDocumentReference;
        return $this;
    }

    /**
     * @param string $buyerReference
     * @return static
     */
    public function setBuyerReference(?string $buyerReference)
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
     * @return static
     */
    public function setAccountingCostCode(?string $accountingCostCode)
    {
        $this->accountingCostCode = $accountingCostCode;
        return $this;
    }

    /**
     * @return staticPeriod
     */
    public function getInvoicePeriod(): ?InvoicePeriod
    {
        return $this->invoicePeriod;
    }

    /**
     * @param InvoicePeriod $invoicePeriod
     * @return static
     */
    public function setInvoicePeriod(?InvoicePeriod $invoicePeriod)
    {
        $this->invoicePeriod = $invoicePeriod;
        return $this;
    }

    /**
     * Get the reference to the invoice that is being credited
     *
     * @return ?BillingReference
     */
    public function getBillingReference(): ?BillingReference
    {
        return $this->billingReference;
    }

    /**
     * Set the reference to the invoice that is being credited
     *
     * @return static
     */
    public function setBillingReference(?BillingReference $billingReference)
    {
        $this->billingReference = $billingReference;
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
     * @return static
     */
    public function setDelivery(?Delivery $delivery)
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
     * @return static
     */
    public function setOrderReference(?OrderReference $orderReference)
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
     * @param ContractDocumentReference $ContractDocumentReference
     * @return static
     */
    public function setContractDocumentReference(?ContractDocumentReference $contractDocumentReference)
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
            Schema::CBC . 'UBLVersionID'    => $this->UBLVersionID,
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

        if ($this->billingReference != null) {
            $writer->write([
                Schema::CAC . 'BillingReference' => $this->billingReference
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

        $writer->write([
            Schema::CAC . 'AccountingSupplierParty' => $this->accountingSupplierParty,
            Schema::CAC . 'AccountingCustomerParty' => $this->accountingCustomerParty,
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
            foreach ($this->paymentMeans as $paymentMeans) {
                $writer->write([
                    Schema::CAC . $paymentMeans->xmlTagName => $paymentMeans
                ]);
            }
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

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $xml
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $mixedContent = mixedContent($reader);

        return static::deserializedTag($mixedContent);
    }

    protected static function deserializedTag(array $mixedContent)
    {
        $ublVersionIdTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'UBLVersionID'))[0] ?? null;
        $idTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'ID'))[0] ?? null;
        $customizationIdTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'CustomizationID'))[0] ?? null;
        $profileIdTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'ProfileID'))[0] ?? null;
        $copyIndicatorTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'CopyIndicator'))[0] ?? null;
        $issueDateTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'IssueDate'))[0] ?? null;
        $dueDateTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'DueDate'))[0] ?? null;
        $documentCurrencyCodeTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'DocumentCurrencyCode'))[0] ?? null;
        $invoiceTypeCodeTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'InvoiceTypeCode'))[0] ?? null;
        $noteTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'Note'))[0] ?? null;
        $taxPointDateTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'TaxPointDate'))[0] ?? null;
        $paymentTermsTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'PaymentTerms'))[0] ?? null;
        $accountingSupplierPartyTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'AccountingSupplierParty'))[0] ?? null;
        $accountingCustomerPartyTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'AccountingCustomerParty'))[0] ?? null;
        $payeePartyTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'PayeeParty'))[0] ?? null;
        $paymentMeansTags = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'PaymentMeans')) ?? [];
        $taxTotalTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'TaxTotal'))[0] ?? null;
        $legalMonetaryTotalTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'LegalMonetaryTotal'))[0] ?? null;
        $invoiceLineTags = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'InvoiceLine')) ?? [];
        $allowanceChargesTags = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'AllowanceCharge')) ?? [];
        $additionalDocumentReferenceTags = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'AdditionalDocumentReference')) ?? [];
        $buyerReferenceTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'BuyerReference'))[0] ?? null;
        $accountingCostCodeTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'AccountingCostCode'))[0] ?? null;
        $invoicePeriodTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'InvoicePeriod'))[0] ?? null;
        $billingReferenceTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'BillingReference'))[0] ?? null;
        $deliveryTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'Delivery'))[0] ?? null;
        $orderReferenceTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'OrderReference'))[0] ?? null;
        $contractDocumentReferenceTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'ContractDocumentReference'))[0] ?? null;

        return (new static())
            ->setUBLVersionID($ublVersionIdTag['value'] ?? null)
            ->setId($idTag['value'] ?? null)
            ->setCustomizationID($customizationIdTag['value'] ?? null)
            ->setProfileID($profileIdTag['value'] ?? null)
            ->setCopyIndicator($copyIndicatorTag['value'] ?? false)
            ->setIssueDate(Carbon::parse($issueDateTag['value'])->toDateTime())
            ->setDueDate(Carbon::parse($dueDateTag['value'] ?? null)->toDateTime())
            ->setDocumentCurrencyCode($documentCurrencyCodeTag['value'] ?? null)
            ->setInvoiceTypeCode($invoiceTypeCodeTag['value'] ?? null)
            ->setNote($noteTag['value'] ?? null)
            ->setTaxPointDate(Carbon::parse($taxPointDateTag['value'])->toDateTime())
            ->setPaymentTerms($paymentTermsTag['value'] ?? null)
            ->setAccountingSupplierParty($accountingSupplierPartyTag['value'] ?? null)
            ->setAccountingCustomerParty($accountingCustomerPartyTag['value'] ?? null)
            ->setPayeeParty($payeePartyTag['value'] ?? null)
            ->setPaymentMeans(!empty($paymentMeansTags) ? array_map(fn ($pm) => $pm['value'], $paymentMeansTags) : null)
            ->setTaxTotal($taxTotalTag['value'] ?? null)
            ->setLegalMonetaryTotal($legalMonetaryTotalTag['value'] ?? null)
            ->setInvoiceLines(!empty($invoiceLineTags) ? array_map(fn ($il) => $il['value'], $invoiceLineTags) : [])
            ->setAllowanceCharges(array_map(fn ($ac) => $ac['value'], $allowanceChargesTags))
            ->setAdditionalDocumentReferences(array_map(fn ($adr) => $adr['value'], $additionalDocumentReferenceTags))
            ->setBuyerReference($buyerReferenceTag['value'] ?? null)
            ->setAccountingCostCode($accountingCostCodeTag['value'] ?? null)
            ->setInvoicePeriod($invoicePeriodTag['value'] ?? null)
            ->setBillingReference($billingReferenceTag['value'] ?? null)
            ->setDelivery($deliveryTag['value'] ?? null)
            ->setOrderReference($orderReferenceTag['value'] ?? null)
            ->setContractDocumentReference($contractDocumentReferenceTag['value'] ?? null)
        ;
    }
}
