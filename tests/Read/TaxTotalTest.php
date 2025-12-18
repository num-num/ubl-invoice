<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\Reader;
use NumNum\UBL\TaxCategory;
use NumNum\UBL\TaxSubTotal;
use NumNum\UBL\TaxTotal;
use PHPUnit\Framework\TestCase;

/**
 * Test TaxTotal deserialization from UBL XML
 */
class TaxTotalTest extends TestCase
{
    /** @test */
    public function testSingleTaxTotalDeserializes()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:ID>456</cbc:ID>
    <cbc:IssueDate>2024-01-01</cbc:IssueDate>
    <cbc:DocumentCurrencyCode>EUR</cbc:DocumentCurrencyCode>
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
        <cbc:TaxAmount currencyID="EUR">20.79</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="EUR">99.00</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="EUR">20.79</cbc:TaxAmount>
            <cac:TaxCategory>
                <cbc:ID>S</cbc:ID>
                <cbc:Name>03</cbc:Name>
                <cbc:Percent>21</cbc:Percent>
                <cac:TaxScheme>
                    <cbc:ID>VAT</cbc:ID>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:LineExtensionAmount currencyID="EUR">99.00</cbc:LineExtensionAmount>
        <cbc:TaxExclusiveAmount currencyID="EUR">99.00</cbc:TaxExclusiveAmount>
        <cbc:TaxInclusiveAmount currencyID="EUR">119.79</cbc:TaxInclusiveAmount>
        <cbc:AllowanceTotalAmount currencyID="EUR">0.00</cbc:AllowanceTotalAmount>
        <cbc:ChargeTotalAmount currencyID="EUR">0.00</cbc:ChargeTotalAmount>
        <cbc:PrepaidAmount currencyID="EUR">0.00</cbc:PrepaidAmount>
        <cbc:PayableAmount currencyID="EUR">119.79</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:InvoiceLine>
        <cbc:ID>1</cbc:ID>
        <cbc:InvoicedQuantity unitCode="C62">1</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="EUR">99.00</cbc:LineExtensionAmount>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="EUR">20.79</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="EUR">99.00</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="EUR">20.79</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:ID>S</cbc:ID>
                    <cbc:Name>03</cbc:Name>
                    <cbc:Percent>21</cbc:Percent>
                    <cac:TaxScheme>
                        <cbc:ID>VAT</cbc:ID>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        <cac:Item>
            <cbc:Description>Our product</cbc:Description>
            <cbc:Name>TOP</cbc:Name>
            <cac:SellersItemIdentification>
                <cbc:ID>P01</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:ClassifiedTaxCategory>
                <cbc:ID>S</cbc:ID>
                <cbc:Name>03</cbc:Name>
                <cbc:Percent>21</cbc:Percent>
                <cac:TaxScheme>
                    <cbc:ID>VAT</cbc:ID>
                </cac:TaxScheme>
            </cac:ClassifiedTaxCategory>
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="EUR">99.00</cbc:PriceAmount>
        </cac:Price>
    </cac:InvoiceLine>
</Invoice>
XML;

        $ublReader = Reader::ubl();
        $invoice = $ublReader->parse($xml);

        $taxTotal = $invoice->getTaxTotal();

        $this->assertInstanceOf(TaxTotal::class, $taxTotal);

        $taxSubtotals = $taxTotal->getTaxSubTotals();

        $this->assertCount(1, $taxSubtotals);

        $firstSubtotal = $taxSubtotals[0];

        $this->assertInstanceOf(TaxSubTotal::class, $firstSubtotal);

        $this->assertEquals(99.00, $firstSubtotal->getTaxableAmount());
        $this->assertEquals(20.79, $firstSubtotal->getTaxAmount());

        $taxCategory = $firstSubtotal->getTaxCategory();

        $this->assertInstanceOf(TaxCategory::class, $taxCategory);

        $this->assertEquals('S', $taxCategory->getID());
        $this->assertEquals(21, $taxCategory->getPercent());
    }
}

