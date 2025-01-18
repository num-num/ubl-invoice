<?php

namespace NumNum\UBL;

use function Sabre\Xml\Deserializer\mixedContent;

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
     * @param AllowanceCharge[] $allowanceCharge
     * @return static
     */
    public function setAllowanceCharges(?AllowanceCharge $allowanceCharge)
    {
        $this->allowanceCharges = $allowanceCharge;
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
            'name'       => Schema::CBC .($this->isCreditNoteLine() ? 'CreditedQuantity' : 'InvoicedQuantity'),
            'value'      => number_format($this->invoicedQuantity, 2, '.', ''),
            'attributes' => $invoicedQuantityAttributes
        ]);

        $writer->write([
            'name'       => Schema::CBC . 'LineExtensionAmount',
            'value'      => number_format($this->lineExtensionAmount ?? 0, 2, '.', ''),
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
     * @param Reader $xml
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
        $idTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'ID'))[0] ?? null;
        $invoicedQuantityTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'InvoicedQuantity'))[0] ?? null;
        $lineExtensionAmountTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'LineExtensionAmount'))[0] ?? null;
        $taxTotalTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'TaxTotal'))[0] ?? null;
        $noteTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'Note'))[0] ?? null;
        $invoicePeriodTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'InvoicePeriod'))[0] ?? null;
        $orderLineReferenceTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'OrderLineReference'))[0] ?? null;
        $itemTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'Item'))[0] ?? null;
        $priceTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'Price'))[0] ?? null;
        $unitCodeTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'UnitCode'))[0] ?? null;
        $unitCodeListIdTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'UnitCodeListID'))[0] ?? null;
        $accountingCostCodeTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'AccountingCostCode'))[0] ?? null;
        $accountingCostTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CBC . 'AccountingCost'))[0] ?? null;
        $allowanceChargeTag = array_values(array_filter($mixedContent, fn ($element) => $element['name'] === Schema::CAC . 'AllowanceCharge'))[0] ?? [];

        return (new static())
            ->setId($idTag['value'] ?? null)
            ->setInvoicedQuantity(isset($invoicedQuantityTag) ? floatval($invoicedQuantityTag['value']) : null)
            ->setLineExtensionAmount(isset($lineExtensionAmountTag) ? floatval($lineExtensionAmountTag['value']) : null)
            ->setTaxTotal($taxTotalTag['value'] ?? null)
            ->setNote($noteTag['value'] ?? null)
            ->setInvoicePeriod($invoicePeriodTag['value'] ?? null)
            ->setOrderLineReference($orderLineReferenceTag['value'] ?? null)
            ->setItem($itemTag['value'] ?? null)
            ->setPrice($priceTag['value'] ?? null)
            ->setUnitCode($unitCodeTag['value'] ?? null)
            ->setUnitCodeListId($unitCodeListIdTag['value'] ?? null)
            ->setAccountingCostCode($accountingCostCodeTag['value'] ?? null)
            ->setAccountingCost($accountingCostTag['value'] ?? null)
            ->setAllowanceCharges($allowanceChargeTag['value'] ?? null)
        ;
    }
}
