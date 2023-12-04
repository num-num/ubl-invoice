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
    private $note;
    private $item;
    private $price;
    private $accountingCostCode;
    private $accountingCost;

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
            [
                'name' => Schema::CBC .
                    ($this->isCreditNoteLine ? 'CreditedQuantity' : 'InvoicedQuantity'),
                'value' => number_format($this->invoicedQuantity, 2, '.', ''),
                'attributes' => $invoicedQuantityAttributes
            ],
            [
                'name' => Schema::CBC . 'LineExtensionAmount',
                'value' => number_format($this->lineExtensionAmount, 2, '.', ''),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
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
