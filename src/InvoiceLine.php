<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

use Doctrine\Common\Collections\ArrayCollection;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class InvoiceLine implements XmlSerializable, XmlDeserializable
{
    public $xmlTagName = 'InvoiceLine';
    private $id;
    protected $invoicedQuantity;
    private $lineExtensionAmount;
    private $unitCode = UnitCode::UNIT;
    private $unitCodeListId;
    private $taxTotal;
    private $invoicePeriod;
    private $orderLineReference;
    private $note;
    private $item;
    private $price;
    private $accountingCostCode;
    private $accountingCost;
    /** @var AllowanceCharge[] $allowanceCharges */
    private $allowanceCharges;

    private function isCreditNoteLine(): bool
    {
        return $this->xmlTagName === 'CreditNoteLine';
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     */
    public function setId(?string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return float
     */
    public function getInvoicedQuantity(): ?float
    {
        return $this->invoicedQuantity;
    }

    /**
     * @param ?float $invoicedQuantity
     * @return static
     */
    public function setInvoicedQuantity(?float $invoicedQuantity)
    {
        $this->invoicedQuantity = $invoicedQuantity;
        return $this;
    }

    /**
     * @return float
     */
    public function getLineExtensionAmount(): ?float
    {
        return $this->lineExtensionAmount;
    }

    /**
     * @param float $lineExtensionAmount
     * @return static
     */
    public function setLineExtensionAmount(?float $lineExtensionAmount)
    {
        $this->lineExtensionAmount = $lineExtensionAmount;
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
    public function setTaxTotal(?TaxTotal $taxTotal)
    {
        $this->taxTotal = $taxTotal;
        return $this;
    }

    /**
     * @return string
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string $note
     * @return static
     */
    public function setNote(?string $note)
    {
        $this->note = $note;
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
     * @return static
     */
    public function setInvoicePeriod(?InvoicePeriod $invoicePeriod)
    {
        $this->invoicePeriod = $invoicePeriod;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderLineReference(): ?OrderLineReference
    {
        return $this->orderLineReference;
    }

    /**
     * @param ?string $orderLineReference
     * @return static
     */
    public function setOrderLineReference(?OrderLineReference $orderLineReference)
    {
        $this->orderLineReference = $orderLineReference;
        return $this;
    }

    /**
     * @return Item
     */
    public function getItem(): ?Item
    {
        return $this->item;
    }

    /**
     * @param Item $item
     * @return static
     */
    public function setItem(?Item $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return Price
     */
    public function getPrice(): ?Price
    {
        return $this->price;
    }

    /**
     * @param Price $price
     * @return static
     */
    public function setPrice(?Price $price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnitCode(): ?string
    {
        return $this->unitCode;
    }

    /**
     * @param string $unitCode
     * @return static
     */
    public function setUnitCode(?string $unitCode)
    {
        $this->unitCode = $unitCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnitCodeListId(): ?string
    {
        return $this->unitCodeListId;
    }

    /**
     * @param string $unitCodeListId
     * @return static
     */
    public function setUnitCodeListId(?string $unitCodeListId)
    {
        $this->unitCodeListId = $unitCodeListId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountingCostCode(): ?string
    {
        return $this->accountingCostCode;
    }

    /**
     * @param string $accountingCostCode
     * @return static
     */
    public function setAccountingCostCode(?string $accountingCostCode)
    {
        $this->accountingCostCode = $accountingCostCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountingCost(): ?string
    {
        return $this->accountingCost;
    }

    /**
     * @param string $accountingCost
     * @return static
     */
    public function setAccountingCost(?string $accountingCost)
    {
        $this->accountingCost = $accountingCost;
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
     * The xmlSerialize method is called during xml writing.
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        $writer->write([
            Schema::CBC . 'ID' => $this->id
        ]);

        if (!empty($this->getNote())) {
            $writer->write([
                Schema::CBC . 'Note' => $this->getNote()
            ]);
        }

        $invoicedQuantityAttributes = [
            'unitCode' => $this->unitCode,
        ];

        if (!empty($this->getUnitCodeListId())) {
            $invoicedQuantityAttributes['unitCodeListID'] = $this->getUnitCodeListId();
        }

        $writer->write([
            'name'       => Schema::CBC . $this->isCreditNoteLine() ? 'CreditedQuantity' : 'InvoicedQuantity',
            'value'      => NumberFormatter::format($this->invoicedQuantity),
            'attributes' => $invoicedQuantityAttributes
        ]);

        $writer->write([
            'name'       => Schema::CBC . 'LineExtensionAmount',
            'value'      => NumberFormatter::format($this->lineExtensionAmount ?? 0, 2),
            'attributes' => [
                'currencyID' => Generator::$currencyID
            ]
        ]);

        if ($this->accountingCostCode !== null) {
            $writer->write([
                Schema::CBC . 'AccountingCostCode' => $this->accountingCostCode
            ]);
        }
        if ($this->accountingCost !== null) {
            $writer->write([
                Schema::CBC . 'AccountingCost' => $this->accountingCost
            ]);
        }
        if ($this->invoicePeriod !== null) {
            $writer->write([
                Schema::CAC . 'InvoicePeriod' => $this->invoicePeriod
            ]);
        }
        if ($this->orderLineReference !== null) {
            $writer->write([
                Schema::CAC . 'OrderLineReference' => $this->orderLineReference
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

        if ($this->item !== null) {
            $writer->write([
                Schema::CAC . 'Item' => $this->item,
            ]);
        }

        if ($this->price !== null) {
            $writer->write([
                Schema::CAC . 'Price' => $this->price
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

    /**
     * @param array $mixedContent
     * @return static
     */
    protected static function deserializedTag(array $mixedContent)
    {
        $collection = new ArrayCollection($mixedContent);

        /** @var ?AllowanceCharge[] ReaderHelper::getArrayValue */
        $allowanceCharges = ReaderHelper::getArrayValue(Schema::CAC . 'AllowanceCharge', $collection);

        return (new static())
            ->setId(ReaderHelper::getTagValue(Schema::CBC . 'ID', $collection))
            ->setInvoicedQuantity(ReaderHelper::getTagValue(Schema::CBC . 'InvoicedQuantity', $collection) !== null ? floatval(ReaderHelper::getTagValue(Schema::CBC . 'InvoicedQuantity', $collection)) : null)
            ->setLineExtensionAmount(ReaderHelper::getTagValue(Schema::CBC . 'LineExtensionAmount', $collection) !== null ? floatval(ReaderHelper::getTagValue(Schema::CBC . 'LineExtensionAmount', $collection)) : null)
            ->setTaxTotal(ReaderHelper::getTagValue(Schema::CAC . 'TaxTotal', $collection))
            ->setNote(ReaderHelper::getTagValue(Schema::CBC . 'Note', $collection))
            ->setInvoicePeriod(ReaderHelper::getTagValue(Schema::CAC . 'InvoicePeriod', $collection))
            ->setOrderLineReference(ReaderHelper::getTagValue(Schema::CAC . 'OrderLineReference', $collection))
            ->setItem(ReaderHelper::getTagValue(Schema::CAC . 'Item', $collection))
            ->setPrice(ReaderHelper::getTagValue(Schema::CAC . 'Price', $collection))
            ->setUnitCode(ReaderHelper::getTagValue(Schema::CBC . 'UnitCode', $collection))
            ->setUnitCodeListId(ReaderHelper::getTagValue(Schema::CBC . 'UnitCodeListID', $collection))
            ->setAccountingCostCode(ReaderHelper::getTagValue(Schema::CBC . 'AccountingCostCode', $collection))
            ->setAccountingCost(ReaderHelper::getTagValue(Schema::CBC . 'AccountingCost', $collection))
            ->setAllowanceCharges($allowanceCharges);
    }
}
