<?php

namespace NumNum\UBL;

use Carbon\Carbon;
use DateTime;
use InvalidArgumentException;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use Doctrine\Common\Collections\ArrayCollection;

use function Sabre\Xml\Deserializer\mixedContent;
class Invoice implements XmlSerializable, XmlDeserializable
{
    public $xmlTagName = "Invoice";
    private $UBLVersionID = "2.1";
    private $customizationID = "1.0";
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
    private $accountingCustomerPartyContact;
    private $payeeParty;
    /** @var PaymentMeans[] $paymentMeans */
    private $paymentMeans;
    private $taxTotal;
    private $legalMonetaryTotal;
    /** @var InvoiceLine[] $invoiceLines */
    protected $invoiceLines;
    private $allowanceCharges;
    private $additionalDocumentReferences = [];
    private $projectReference;
    private $documentCurrencyCode = "EUR";
    private $buyerReference;
    private $accountingCostCode;
    private $invoicePeriod;
    private $billingReference;
    private $delivery;
    private $orderReference;
    private $contractDocumentReference;
    private $despatchDocumentReference;
    private $receiptDocumentReference;
    private $originatorDocumentReference;

    /**
     * @return string
     */
    public function getUBLVersionId(): ?string
    {
        return $this->UBLVersionID;
    }

    /**
     * @param string $UBLVersionID
     *                             eg. '2.0', '2.1', '2.2', ...
     * @return static
     */
    public function setUBLVersionId(?string $UBLVersionID)
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
    public function setCustomizationId(?string $customizationID)
    {
        $this->customizationID = $customizationID;
        return $this;
    }

    /**
     * @param mixed $profileID
     * @return static
     */
    public function setProfileId(?string $profileID)
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
     * @return string
     */
    public function getDocumentCurrencyCode(): ?string
    {
        return $this->documentCurrencyCode;
    }

    /**
     * @param mixed $currencyCode
     * @return static
     */
    public function setDocumentCurrencyCode(?string $currencyCode = "EUR")
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
    public function setInvoiceTypeCode(?string $invoiceTypeCode)
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
    public function setAccountingSupplierParty(
        AccountingParty $accountingSupplierParty,
    ) {
        $this->accountingSupplierParty = $accountingSupplierParty;
        return $this;
    }

    /**
     * @return string
     */
    public function getSupplierAssignedAccountID(): ?string
    {
        return $this->supplierAssignedAccountID;
    }

    /**
     * @param string $supplierAssignedAccountID
     * @return Invoice
     */
    public function setSupplierAssignedAccountID(
        string $supplierAssignedAccountID,
    ): Invoice {
        $this->supplierAssignedAccountID = $supplierAssignedAccountID;
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
    public function setAccountingCustomerParty(
        AccountingParty $accountingCustomerParty,
    ) {
        $this->accountingCustomerParty = $accountingCustomerParty;
        return $this;
    }

    /**
     * @return ?Contact
     */
    public function getAccountingCustomerPartyContact(): ?Contact
    {
        return $this->accountingCustomerPartyContact;
    }

    /**
     * @param Contact $accountingCustomerPartyContact
     * @return Invoice
     */
    public function setAccountingCustomerPartyContact(
        Contact $accountingCustomerPartyContact,
    ): Invoice {
        $this->accountingCustomerPartyContact = $accountingCustomerPartyContact;
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
    public function setLegalMonetaryTotal(
        LegalMonetaryTotal $legalMonetaryTotal,
    ) {
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
    public function setAdditionalDocumentReference(
        AdditionalDocumentReference $additionalDocumentReference,
    ) {
        $this->additionalDocumentReferences = [$additionalDocumentReference];
        return $this;
    }

    /**
     * @param AdditionalDocumentReference $additionalDocumentReference
     * @return static
     */
    public function setAdditionalDocumentReferences(
        array $additionalDocumentReference,
    ) {
        $this->additionalDocumentReferences = $additionalDocumentReference;
        return $this;
    }

    /**
     * @param AdditionalDocumentReference $additionalDocumentReference
     * @return static
     */
    public function addAdditionalDocumentReference(
        AdditionalDocumentReference $additionalDocumentReference,
    ) {
        $this->additionalDocumentReferences[] = $additionalDocumentReference;
        return $this;
    }

    /**
     * @param ProjectReference $projectReference
     * @return Invoice
     */
    public function setProjectReference(
        ProjectReference $projectReference,
    ): Invoice {
        $this->projectReference = $projectReference;
        return $this;
    }

    /**
     * @return ProjectReference projectReference
     */
    public function getProjectReference(): ?ProjectReference
    {
        return $this->projectReference;
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
    public function setContractDocumentReference(
        ?ContractDocumentReference $contractDocumentReference,
    ) {
        $this->contractDocumentReference = $contractDocumentReference;
        return $this;
    }

    /**
     * @return DespatchDocumentReference
     */
    public function getDespatchDocumentReference(): ?DespatchDocumentReference
    {
        return $this->despatchDocumentReference;
    }

    /**
     * @param DespatchDocumentReference $despatchDocumentReference
     * @return static
     */
    public function setDespatchDocumentReference(
        ?DespatchDocumentReference $despatchDocumentReference,
    ) {
        $this->despatchDocumentReference = $despatchDocumentReference;
        return $this;
    }

    /**
     * @return ReceiptDocumentReference
     */
    public function getReceiptDocumentReference(): ?ReceiptDocumentReference
    {
        return $this->receiptDocumentReference;
    }

    /**
     * @param ReceiptDocumentReference $receiptDocumentReference
     * @return static
     */
    public function setReceiptDocumentReference(
        ?ReceiptDocumentReference $receiptDocumentReference,
    ) {
        $this->receiptDocumentReference = $receiptDocumentReference;
        return $this;
    }

    /**
     * @return OriginatorDocumentReference
     */
    public function getOriginatorDocumentReference(): ?OriginatorDocumentReference
    {
        return $this->originatorDocumentReference;
    }

    /**
     * @param OriginatorDocumentReference $originatorDocumentReference
     * @return static
     */
    public function setOriginatorDocumentReference(
        ?OriginatorDocumentReference $originatorDocumentReference,
    ) {
        $this->originatorDocumentReference = $originatorDocumentReference;
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
            throw new InvalidArgumentException("Missing invoice id");
        }

        if (!$this->issueDate instanceof DateTime) {
            throw new InvalidArgumentException("Invalid invoice issueDate");
        }

        if ($this->invoiceTypeCode === null) {
            throw new InvalidArgumentException(
                "Missing invoice invoiceTypeCode",
            );
        }

        if ($this->accountingSupplierParty === null) {
            throw new InvalidArgumentException(
                "Missing invoice accountingSupplierParty",
            );
        }

        if ($this->accountingCustomerParty === null) {
            throw new InvalidArgumentException(
                "Missing invoice accountingCustomerParty",
            );
        }

        if ($this->invoiceLines === null) {
            throw new InvalidArgumentException("Missing invoice lines");
        }

        if ($this->legalMonetaryTotal === null) {
            throw new InvalidArgumentException(
                "Missing invoice LegalMonetaryTotal",
            );
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
            Schema::CBC . "UBLVersionID" => $this->UBLVersionID,
            Schema::CBC . "CustomizationID" => $this->customizationID,
        ]);

        if ($this->profileID !== null) {
            $writer->write([
                Schema::CBC . "ProfileID" => $this->profileID,
            ]);
        }

        $writer->write([
            Schema::CBC . "ID" => $this->id,
        ]);

        if ($this->copyIndicator !== null) {
            $writer->write([
                Schema::CBC . "CopyIndicator" => $this->copyIndicator
                    ? "true"
                    : "false",
            ]);
        }

        $writer->write([
            Schema::CBC . "IssueDate" => $this->issueDate->format("Y-m-d"),
        ]);

        if ($this->dueDate !== null && $this->xmlTagName === "Invoice") {
            $writer->write([
                Schema::CBC . "DueDate" => $this->dueDate->format("Y-m-d"),
            ]);
        }

        if ($this->invoiceTypeCode !== null) {
            $writer->write([
                Schema::CBC .
                $this->xmlTagName .
                "TypeCode" => $this->invoiceTypeCode,
            ]);
        }

        if ($this->note !== null) {
            $writer->write([
                Schema::CBC . "Note" => $this->note,
            ]);
        }

        if ($this->taxPointDate !== null) {
            $writer->write([
                Schema::CBC . "TaxPointDate" => $this->taxPointDate->format(
                    "Y-m-d",
                ),
            ]);
        }

        $writer->write([
            Schema::CBC . "DocumentCurrencyCode" => $this->documentCurrencyCode,
        ]);

        if ($this->accountingCostCode !== null) {
            $writer->write([
                Schema::CBC . "AccountingCostCode" => $this->accountingCostCode,
            ]);
        }

        if ($this->buyerReference != null) {
            $writer->write([
                Schema::CBC . "BuyerReference" => $this->buyerReference,
            ]);
        }

        if ($this->invoicePeriod != null) {
            $writer->write([
                Schema::CAC . "InvoicePeriod" => $this->invoicePeriod,
            ]);
        }

        if ($this->orderReference != null) {
            $writer->write([
                Schema::CAC . "OrderReference" => $this->orderReference,
            ]);
        }

        if ($this->billingReference != null) {
            $writer->write([
                Schema::CAC . "BillingReference" => $this->billingReference,
            ]);
        }

        if ($this->contractDocumentReference !== null) {
            $writer->write([
                Schema::CAC .
                "ContractDocumentReference" => $this->contractDocumentReference,
            ]);
        }

        if ($this->despatchDocumentReference !== null) {
            $writer->write([
                Schema::CAC .
                "DespatchDocumentReference" => $this->despatchDocumentReference,
            ]);
        }

        if ($this->receiptDocumentReference !== null) {
            $writer->write([
                Schema::CAC .
                "ReceiptDocumentReference" => $this->receiptDocumentReference,
            ]);
        }

        if (!empty($this->additionalDocumentReferences)) {
            foreach (
                $this->additionalDocumentReferences
                as $additionalDocumentReference
            ) {
                $writer->write([
                    Schema::CAC .
                    "AdditionalDocumentReference" => $additionalDocumentReference,
                ]);
            }
        }

        if ($this->originatorDocumentReference !== null) {
            $writer->write([
                Schema::CAC .
                "OriginatorDocumentReference" => $this->originatorDocumentReference,
            ]);
        }

        if ($this->projectReference != null) {
            $writer->write([
                Schema::CAC . "ProjectReference" => $this->projectReference,
            ]);
        }

        $writer->write([
            Schema::CAC .
            "AccountingSupplierParty" => $this->accountingSupplierParty,
            Schema::CAC .
            "AccountingCustomerParty" => $this->accountingCustomerParty,
        ]);

        if ($this->payeeParty != null) {
            $writer->write([
                Schema::CAC . "PayeeParty" => $this->payeeParty,
            ]);
        }

        if ($this->delivery != null) {
            $writer->write([
                Schema::CAC . "Delivery" => $this->delivery,
            ]);
        }

        if ($this->paymentMeans !== null) {
            foreach ($this->paymentMeans as $paymentMeans) {
                $writer->write([
                    Schema::CAC . $paymentMeans->xmlTagName => $paymentMeans,
                ]);
            }
        }

        if ($this->paymentTerms !== null) {
            $writer->write([
                Schema::CAC . "PaymentTerms" => $this->paymentTerms,
            ]);
        }

        if ($this->allowanceCharges !== null) {
            foreach ($this->allowanceCharges as $allowanceCharge) {
                $writer->write([
                    Schema::CAC . "AllowanceCharge" => $allowanceCharge,
                ]);
            }
        }

        if ($this->taxTotal !== null) {
            $writer->write([
                Schema::CAC . "TaxTotal" => $this->taxTotal,
            ]);
        }

        $writer->write([
            Schema::CAC . "LegalMonetaryTotal" => $this->legalMonetaryTotal,
        ]);

        foreach ($this->invoiceLines as $invoiceLine) {
            $writer->write([
                Schema::CAC . $invoiceLine->xmlTagName => $invoiceLine,
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
        $collection = new ArrayCollection($mixedContent);

        /** @var ?AccountingParty $accountingSupplierParty */
        $accountingSupplierParty = ReaderHelper::getTagValue(
            Schema::CAC . "AccountingSupplierParty",
            $collection,
        );

        /** @var ?AccountingParty $accountingCustomerParty */
        $accountingCustomerParty = ReaderHelper::getTagValue(
            Schema::CAC . "AccountingCustomerParty",
            $collection,
        );

        /** @var ?TaxTotal $taxTotal */
        $taxTotal = ReaderHelper::getTagValue(
            Schema::CAC . "TaxTotal",
            $collection,
        );

        /** @var ?LegalMonetaryTotal $legalMonetaryTotal */
        $legalMonetaryTotal = ReaderHelper::getTagValue(
            Schema::CAC . "LegalMonetaryTotal",
            $collection,
        );

        return new static()
            ->setUBLVersionId(
                ReaderHelper::getTagValue(
                    Schema::CBC . "UBLVersionID",
                    $collection,
                ),
            )
            ->setId(ReaderHelper::getTagValue(Schema::CBC . "ID", $collection))
            ->setCustomizationId(
                ReaderHelper::getTagValue(
                    Schema::CBC . "CustomizationID",
                    $collection,
                ),
            )
            ->setProfileId(
                ReaderHelper::getTagValue(
                    Schema::CBC . "ProfileID",
                    $collection,
                ),
            )
            ->setCopyIndicator(
                ReaderHelper::getTagValue(
                    Schema::CBC . "CopyIndicator",
                    $collection,
                ) ?? false,
            )
            ->setIssueDate(
                Carbon::parse(
                    ReaderHelper::getTagValue(
                        Schema::CBC . "IssueDate",
                        $collection,
                    ),
                )->toDateTime(),
            )
            ->setDueDate(
                Carbon::parse(
                    ReaderHelper::getTagValue(
                        Schema::CBC . "DueDate",
                        $collection,
                    ),
                )->toDateTime(),
            )
            ->setDocumentCurrencyCode(
                ReaderHelper::getTagValue(
                    Schema::CBC . "DocumentCurrencyCode",
                    $collection,
                ),
            )
            ->setInvoiceTypeCode(
                ReaderHelper::getTagValue(
                    Schema::CBC . "InvoiceTypeCode",
                    $collection,
                ),
            )
            ->setNote(
                ReaderHelper::getTagValue(Schema::CBC . "Note", $collection),
            )
            ->setTaxPointDate(
                Carbon::parse(
                    ReaderHelper::getTagValue(
                        Schema::CBC . "TaxPointDate",
                        $collection,
                    ),
                )->toDateTime(),
            )
            ->setPaymentTerms(
                ReaderHelper::getTagValue(
                    Schema::CAC . "PaymentTerms",
                    $collection,
                ),
            )
            ->setAccountingSupplierParty($accountingSupplierParty)
            ->setAccountingCustomerParty($accountingCustomerParty)
            ->setPayeeParty(
                ReaderHelper::getTagValue(
                    Schema::CAC . "PayeeParty",
                    $collection,
                ),
            )
            ->setPaymentMeans(
                ReaderHelper::getArrayValue(
                    Schema::CAC . "PaymentMeans",
                    $collection,
                ),
            )
            ->setTaxTotal($taxTotal)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setInvoiceLines(
                ReaderHelper::getArrayValue(
                    Schema::CAC . "InvoiceLine",
                    $collection,
                ),
            )
            ->setAllowanceCharges(
                ReaderHelper::getArrayValue(
                    Schema::CAC . "AllowanceCharge",
                    $collection,
                ),
            )
            ->setAdditionalDocumentReferences(
                ReaderHelper::getArrayValue(
                    Schema::CAC . "AdditionalDocumentReference",
                    $collection,
                ),
            )
            ->setBuyerReference(
                ReaderHelper::getTagValue(
                    Schema::CBC . "BuyerReference",
                    $collection,
                ),
            )
            ->setAccountingCostCode(
                ReaderHelper::getTagValue(
                    Schema::CBC . "AccountingCostCode",
                    $collection,
                ),
            )
            ->setInvoicePeriod(
                ReaderHelper::getTagValue(
                    Schema::CAC . "InvoicePeriod",
                    $collection,
                ),
            )
            ->setBillingReference(
                ReaderHelper::getTagValue(
                    Schema::CAC . "BillingReference",
                    $collection,
                ),
            )
            ->setDelivery(
                ReaderHelper::getTagValue(
                    Schema::CAC . "Delivery",
                    $collection,
                ),
            )
            ->setOrderReference(
                ReaderHelper::getTagValue(
                    Schema::CAC . "OrderReference",
                    $collection,
                ),
            )
            ->setContractDocumentReference(
                ReaderHelper::getTagValue(
                    Schema::CAC . "ContractDocumentReference",
                    $collection,
                ),
            )
            ->setDespatchDocumentReference(
                ReaderHelper::getTagValue(
                    Schema::CAC . "DespatchDocumentReference",
                    $collection,
                ),
            )
            ->setReceiptDocumentReference(
                ReaderHelper::getTagValue(
                    Schema::CAC . "ReceiptDocumentReference",
                    $collection,
                ),
            )
            ->setOriginatorDocumentReference(
                ReaderHelper::getTagValue(
                    Schema::CAC . "OriginatorDocumentReference",
                    $collection,
                ),
            );
    }
}
