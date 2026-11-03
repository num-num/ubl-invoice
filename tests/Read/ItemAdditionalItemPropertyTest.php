<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\AdditionalItemProperty;
use NumNum\UBL\Invoice;
use NumNum\UBL\Reader;
use PHPUnit\Framework\TestCase;

class ItemAdditionalItemPropertyTest extends TestCase
{
    /** @test */
    public function testAdditionalItemPropertiesAreHydratedAsObjects()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
    xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
    xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">
    <cbc:ID>12345</cbc:ID>
    <cbc:IssueDate>2024-01-01</cbc:IssueDate>
    <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PartyName>
                <cbc:Name>Supplier</cbc:Name>
            </cac:PartyName>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PartyName>
                <cbc:Name>Customer</cbc:Name>
            </cac:PartyName>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="EUR">21.00</cbc:TaxAmount>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:PayableAmount currencyID="EUR">121.00</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:InvoiceLine>
        <cbc:ID>1</cbc:ID>
        <cbc:InvoicedQuantity unitCode="C62">1</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="EUR">100</cbc:LineExtensionAmount>
        <cac:Item>
            <cbc:Name>Test Product</cbc:Name>
            <cac:AdditionalItemProperty>
                <cbc:Name>Color</cbc:Name>
                <cbc:Value>Blue</cbc:Value>
            </cac:AdditionalItemProperty>
        </cac:Item>
    </cac:InvoiceLine>
</Invoice>
XML;

        $ublReader = Reader::ubl();
        $invoice = $ublReader->parse($xml);

        $this->assertNotNull($invoice);
        $this->assertInstanceOf(Invoice::class, $invoice);

        $invoiceLines = $invoice->getInvoiceLines();
        $this->assertNotEmpty($invoiceLines);

        $firstLine = array_values($invoiceLines)[0];
        $item = $firstLine->getItem();
        $this->assertNotNull($item);

        $additionalItemProperties = $item->getAdditionalItemProperties();
        $this->assertIsArray($additionalItemProperties);
        $this->assertCount(1, $additionalItemProperties);
        $firstAdditionalItemProperty = reset($additionalItemProperties);
        $this->assertInstanceOf(AdditionalItemProperty::class, $firstAdditionalItemProperty);
        $this->assertEquals('Color', $firstAdditionalItemProperty->getName());
        $this->assertEquals('Blue', $firstAdditionalItemProperty->getValue());
    }
}
