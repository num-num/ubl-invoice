<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class InvoiceLine implements XmlSerializable
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

    // See CreditNoteLine.php
    protected $isCreditNoteLine = false;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return InvoiceLine
     */
    public function setId(?string $id): InvoiceLine
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
     * @return InvoiceLine
     */
    public function setInvoicedQuantity(?float $invoicedQuantity): InvoiceLine
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
     * @return InvoiceLine
     */
    public function setLineExtensionAmount(?float $lineExtensionAmount): InvoiceLine
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
     * @return InvoiceLine
     */
    public function setTaxTotal(?TaxTotal $taxTotal): InvoiceLine
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
     * @return InvoiceLine
     */
    public function setNote(?string $note): InvoiceLine
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
     * @return InvoiceLine
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
     * @return OrderLineReference
     */
    public function setOrderLineReference(?OrderLineReference $orderLineReference): InvoiceLine
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
     * @return InvoiceLine
     */
    public function setItem(Item $item): InvoiceLine
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
     * @return InvoiceLine
     */
    public function setPrice(?Price $price): InvoiceLine
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
     * @return InvoiceLine
     */
    public function setUnitCode(?string $unitCode): InvoiceLine
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
     * @return InvoiceLine
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
     * @return InvoiceLine
     */
    public function setAccountingCostCode(?string $accountingCostCode): InvoiceLine
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
     * @return InvoiceLine
     */
    public function setAccountingCost(?string $accountingCost): InvoiceLine
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
     * @return InvoiceLine
     */
    public function setAllowanceCharges(array $allowanceCharges): InvoiceLine
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
            'name' => Schema::CBC .
                ($this->isCreditNoteLine ? 'CreditedQuantity' : 'InvoicedQuantity'),
            'value' => NumberFormatter::format($this->invoicedQuantity),
            'attributes' => $invoicedQuantityAttributes
        ]);

        $writer->write([
            'name' => Schema::CBC . 'LineExtensionAmount',
            'value' => NumberFormatter::format($this->lineExtensionAmount ?? 0, 2),
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
}
