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
    public string $xmlTagName = "Invoice";
    private ?string $UBLVersionID = "2.1";
    private ?string $customizationID = "1.0";
    private ?string $profileID = null;
    private ?string $id = null;
    private ?bool $copyIndicator = null;
    private ?DateTime $issueDate = null;
    private ?DateTime $issueTime = null;
    protected ?int $invoiceTypeCode = InvoiceTypeCode::INVOICE;
    private ?string $note = null;
    private ?DateTime $taxPointDate = null;
    private ?DateTime $dueDate = null;
    private ?PaymentTerms $paymentTerms = null;
    private ?AccountingParty $accountingSupplierParty = null;
    private ?AccountingParty $accountingCustomerParty = null;
    private ?Contact $accountingCustomerPartyContact = null;
    private ?PayeeParty $payeeParty = null;
    /** @var PaymentMeans[] $paymentMeans */
    private ?array $paymentMeans = null;
    private ?TaxTotal $taxTotal = null;
    private ?LegalMonetaryTotal $legalMonetaryTotal = null;
    /** @var InvoiceLine[]|null $invoiceLines */
    protected ?array $invoiceLines = null;
    private ?array $allowanceCharges = null;
    private array $additionalDocumentReferences = [];
    private ?ProjectReference $projectReference = null;
    private ?string $documentCurrencyCode = "EUR";
    private ?string $taxCurrencyCode = null;
    private ?string $buyerReference = null;
    private ?string $accountingCostCode = null;
    private ?InvoicePeriod $invoicePeriod = null;
    private ?BillingReference $billingReference = null;
    private ?Delivery $delivery = null;
    private ?OrderReference $orderReference = null;
    private ?ContractDocumentReference $contractDocumentReference = null;
    private ?DespatchDocumentReference $despatchDocumentReference = null;
    private ?ReceiptDocumentReference $receiptDocumentReference = null;
    private ?OriginatorDocumentReference $originatorDocumentReference = null;

    /**
     * @return string|null
     */
    public function getUBLVersionId(): ?string
    {
        return $this->UBLVersionID;
    }

    /**
     * @param string|null $UBLVersionID
     *                             eg. '2.0', '2.1', '2.2', ...
     * @return static
     */
    public function setUBLVersionId(?string $UBLVersionID)
    {
        $this->UBLVersionID = $UBLVersionID;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return static
     */
    public function setId(?string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomizationId(): ?string
    {
        return $this->customizationID;
    }

    /**
     * @param string|null $customizationID
     * @return static
     */
    public function setCustomizationId(?string $customizationID)
    {
        $this->customizationID = $customizationID;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfileId(): ?string
    {
        return $this->profileID;
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
     * @return DateTime|null
     */
    public function getIssueDate(): ?DateTime
    {
        return $this->issueDate;
    }

    /**
     * @param DateTime|null $issueDate
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
    public function getIssueTime() : ?DateTime
    {
        return $this->issueTime ?? null;
    }

    /**
     * @param DateTime|null $issueTime
     * @return static
     */
    public function setIssueTime(?DateTime $issueTime = null) : self
    {
        $this->issueTime = $issueTime;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDueDate(): ?DateTime
    {
        return $this->dueDate;
    }

    /**
     * @param DateTime|null $dueDate
     * @return static
     */
    public function setDueDate(?DateTime $dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDocumentCurrencyCode(): ?string
    {
        return $this->documentCurrencyCode;
    }

    /**
     * @param string|null $currencyCode
     * @return static
     */
    public function setDocumentCurrencyCode(?string $currencyCode = "EUR")
    {
        $this->documentCurrencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTaxCurrencyCode(): ?string
    {
        return $this->taxCurrencyCode;
    }

    /**
     * @param mixed $currencyCode
     * @return static
     */
    public function setTaxCurrencyCode(?string $currencyCode)
    {
        $this->taxCurrencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInvoiceTypeCode(): ?int
    {
        return $this->invoiceTypeCode;
    }

    /**
     * @param int|null $invoiceTypeCode
     *                             See also: src/InvoiceTypeCode.php
     * @return static
     */
    public function setInvoiceTypeCode(?int $invoiceTypeCode)
    {
        $this->invoiceTypeCode = $invoiceTypeCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string|null $note
     * @return static
     */
    public function setNote(?string $note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getTaxPointDate(): ?DateTime
    {
        return $this->taxPointDate;
    }

    /**
     * @param DateTime|null $taxPointDate
     * @return static
     */
    public function setTaxPointDate(?DateTime $taxPointDate)
    {
        $this->taxPointDate = $taxPointDate;
        return $this;
    }

    /**
     * @return PaymentTerms|null
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
     * @return AccountingParty|null
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
        AccountingParty $accountingSupplierParty
    ) {
        $this->accountingSupplierParty = $accountingSupplierParty;
        return $this;
    }

    /**
     * @return AccountingParty|null
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
        AccountingParty $accountingCustomerParty
    ) {
        $this->accountingCustomerParty = $accountingCustomerParty;
        return $this;
    }

    /**
     * @return Contact|null
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
        Contact $accountingCustomerPartyContact
    ): Invoice {
        $this->accountingCustomerPartyContact = $accountingCustomerPartyContact;
        return $this;
    }

    /**
     * @return PayeeParty|null
     */
    public function getPayeeParty(): ?PayeeParty
    {
        return $this->payeeParty;
    }

    /**
     * @param PayeeParty|null $payeeParty
     * @return static
     */
    public function setPayeeParty(?PayeeParty $payeeParty)
    {
        $this->payeeParty = $payeeParty;
        return $this;
    }

    /**
     * @return PaymentMeans[]|null
     */
    public function getPaymentMeans(): ?array
    {
        return $this->paymentMeans;
    }

    /**
     * @param PaymentMeans[]|null $paymentMeans
     * @return static
     */
    public function setPaymentMeans(?array $paymentMeans)
    {
        $this->paymentMeans = $paymentMeans;
        return $this;
    }

    /**
     * @return TaxTotal|null
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
     * @return LegalMonetaryTotal|null
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
        LegalMonetaryTotal $legalMonetaryTotal
    ) {
        $this->legalMonetaryTotal = $legalMonetaryTotal;
        return $this;
    }

    /**
     * @return InvoiceLine[]|null
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
     * @return AllowanceCharge[]|null
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
     * @return AdditionalDocumentReference|null
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
        AdditionalDocumentReference $additionalDocumentReference
    ) {
        $this->additionalDocumentReferences = [$additionalDocumentReference];
        return $this;
    }

    /**
     * @param array $additionalDocumentReference
     * @return static
     */
    public function setAdditionalDocumentReferences(
        array $additionalDocumentReference
    ) {
        $this->additionalDocumentReferences = $additionalDocumentReference;
        return $this;
    }

    /**
     * @param AdditionalDocumentReference $additionalDocumentReference
     * @return static
     */
    public function addAdditionalDocumentReference(
        AdditionalDocumentReference $additionalDocumentReference
    ) {
        $this->additionalDocumentReferences[] = $additionalDocumentReference;
        return $this;
    }

    /**
     * @param ProjectReference $projectReference
     * @return Invoice
     */
    public function setProjectReference(
        ProjectReference $projectReference
    ): Invoice {
        $this->projectReference = $projectReference;
        return $this;
    }

    /**
     * @return ProjectReference|null
     */
    public function getProjectReference(): ?ProjectReference
    {
        return $this->projectReference;
    }

    /**
     * @param string|null $buyerReference
     * @return static
     */
    public function setBuyerReference(?string $buyerReference)
    {
        $this->buyerReference = $buyerReference;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBuyerReference(): ?string
    {
        return $this->buyerReference;
    }

    /**
     * @return string|null
     */
    public function getAccountingCostCode(): ?string
    {
        return $this->accountingCostCode;
    }

    /**
     * @param string|null $accountingCostCode
     * @return static
     */
    public function setAccountingCostCode(?string $accountingCostCode)
    {
        $this->accountingCostCode = $accountingCostCode;
        return $this;
    }

    /**
     * @return InvoicePeriod|null
     */
    public function getInvoicePeriod(): ?InvoicePeriod
    {
        return $this->invoicePeriod;
    }

    /**
     * @param InvoicePeriod|null $invoicePeriod
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
     * @return BillingReference|null
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
     * @return Delivery|null
     */
    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    /**
     * @param Delivery|null $delivery
     * @return static
     */
    public function setDelivery(?Delivery $delivery)
    {
        $this->delivery = $delivery;
        return $this;
    }

    /**
     * @return OrderReference|null
     */
    public function getOrderReference(): ?OrderReference
    {
        return $this->orderReference;
    }

    /**
     * @param OrderReference|null $orderReference
     * @return static
     */
    public function setOrderReference(?OrderReference $orderReference)
    {
        $this->orderReference = $orderReference;
        return $this;
    }

    /**
     * @return ContractDocumentReference|null
     */
    public function getContractDocumentReference(): ?ContractDocumentReference
    {
        return $this->contractDocumentReference;
    }

    /**
     * @param ContractDocumentReference|null $contractDocumentReference
     * @return static
     */
    public function setContractDocumentReference(?ContractDocumentReference $contractDocumentReference)
    {
        $this->contractDocumentReference = $contractDocumentReference;
        return $this;
    }

    /**
     * @return DespatchDocumentReference|null
     */
    public function getDespatchDocumentReference(): ?DespatchDocumentReference
    {
        return $this->despatchDocumentReference;
    }

    /**
     * @param DespatchDocumentReference|null $despatchDocumentReference
     * @return static
     */
    public function setDespatchDocumentReference(
        ?DespatchDocumentReference $despatchDocumentReference
    ) {
        $this->despatchDocumentReference = $despatchDocumentReference;
        return $this;
    }

    /**
     * @return ReceiptDocumentReference|null
     */
    public function getReceiptDocumentReference(): ?ReceiptDocumentReference
    {
        return $this->receiptDocumentReference;
    }

    /**
     * @param ReceiptDocumentReference|null $receiptDocumentReference
     * @return static
     */
    public function setReceiptDocumentReference(
        ?ReceiptDocumentReference $receiptDocumentReference
    ) {
        $this->receiptDocumentReference = $receiptDocumentReference;
        return $this;
    }

    /**
     * @return OriginatorDocumentReference|null
     */
    public function getOriginatorDocumentReference(): ?OriginatorDocumentReference
    {
        return $this->originatorDocumentReference;
    }

    /**
     * @param OriginatorDocumentReference|null $originatorDocumentReference
     * @return static
     */
    public function setOriginatorDocumentReference(
        ?OriginatorDocumentReference $originatorDocumentReference
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

        if (isset($this->issueTime)) {
            $writer->write([
                Schema::CBC . 'IssueTime' => $this->issueTime->format('H:i:s'),
            ]);
        }

        if ($this->dueDate !== null && $this->xmlTagName === "Invoice") {
            $writer->write([
                Schema::CBC . "DueDate" => $this->dueDate->format("Y-m-d"),
            ]);
        }

        // DebitNote does not have a TypeCode element in UBL 2.1
        if ($this->invoiceTypeCode !== null && $this->xmlTagName !== 'DebitNote') {
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
            foreach ($this->additionalDocumentReferences as $additionalDocumentReference) {
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

        // DebitNote uses RequestedMonetaryTotal instead of LegalMonetaryTotal
        $monetaryTotalTagName = $this->xmlTagName === 'DebitNote' ? 'RequestedMonetaryTotal' : 'LegalMonetaryTotal';
        $writer->write([
            Schema::CAC . $monetaryTotalTagName => $this->legalMonetaryTotal,
        ]);

        foreach ($this->invoiceLines as $invoiceLine) {
            $writer->write([
                Schema::CAC . $invoiceLine->xmlTagName => $invoiceLine,
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

        return (new static())
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
            ->setIssueTime(
                Carbon::parse(
                    ReaderHelper::getTagValue(
                        Schema::CBC . "IssueTime",
                        $collection,
                    ),
                )->toDateTime(),
            )
            ->setDueDate(
                ($dueDate = ReaderHelper::getTagValue(
                    Schema::CBC . "DueDate",
                    $collection,
                )) !== null ? Carbon::parse($dueDate)->toDateTime() : null
            )
            ->setDocumentCurrencyCode(
                ReaderHelper::getTagValue(
                    Schema::CBC . "DocumentCurrencyCode",
                    $collection,
                ),
            )
            ->setTaxCurrencyCode(
                ReaderHelper::getTagValue(
                    Schema::CBC . "TaxCurrencyCode",
                    $collection,
                ),
            )
            ->setInvoiceTypeCode(
                ($typeCode = ReaderHelper::getTagValue(
                    Schema::CBC . "InvoiceTypeCode",
                    $collection,
                )) !== null ? (int) $typeCode : null,
            )
            ->setNote(
                ReaderHelper::getTagValue(Schema::CBC . "Note", $collection),
            )
            ->setTaxPointDate(
                ($taxPointDate = ReaderHelper::getTagValue(
                    Schema::CBC . "TaxPointDate",
                    $collection,
                )) !== null ? Carbon::parse($taxPointDate)->toDateTime() : null
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
