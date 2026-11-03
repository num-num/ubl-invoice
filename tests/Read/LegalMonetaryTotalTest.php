<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\Invoice;
use NumNum\UBL\Reader;
use PHPUnit\Framework\TestCase;

/**
 * Test LegalMonetaryTotal deserialization from UBL XML
 */
class LegalMonetaryTotalTest extends TestCase
{
    /** @test */
    public function testPayableRoundingAmountDeserializesFromItsOwnElement()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:ID>123</cbc:ID>
    <cbc:IssueDate>2024-01-01</cbc:IssueDate>
    <cbc:InvoiceTypeCode>380</cbc:InvoiceTypeCode>
    <cbc:DocumentCurrencyCode>EUR</cbc:DocumentCurrencyCode>
    <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PostalAddress>
                <cbc:CityName>Gent</cbc:CityName>
                <cac:Country>
                    <cbc:IdentificationCode>BE</cbc:IdentificationCode>
                </cac:Country>
            </cac:PostalAddress>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PostalAddress>
                <cbc:CityName>Amsterdam</cbc:CityName>
                <cac:Country>
                    <cbc:IdentificationCode>NL</cbc:IdentificationCode>
                </cac:Country>
            </cac:PostalAddress>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="EUR">21.00</cbc:TaxAmount>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:LineExtensionAmount currencyID="EUR">100.00</cbc:LineExtensionAmount>
        <cbc:TaxExclusiveAmount currencyID="EUR">100.00</cbc:TaxExclusiveAmount>
        <cbc:TaxInclusiveAmount currencyID="EUR">121.00</cbc:TaxInclusiveAmount>
        <cbc:PayableRoundingAmount currencyID="EUR">0.05</cbc:PayableRoundingAmount>
        <cbc:PayableAmount currencyID="EUR">121.05</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:InvoiceLine>
        <cbc:ID>1</cbc:ID>
        <cbc:InvoicedQuantity unitCode="C62">1</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="EUR">100.00</cbc:LineExtensionAmount>
    </cac:InvoiceLine>
</Invoice>
XML;

        $invoice = Reader::ubl()->parse($xml);

        $this->assertInstanceOf(Invoice::class, $invoice);

        $legalMonetaryTotal = $invoice->getLegalMonetaryTotal();

        $this->assertEquals(0.05, $legalMonetaryTotal->getPayableRoundingAmount());
        $this->assertEquals(121.05, $legalMonetaryTotal->getPayableAmount());
        $this->assertEquals(121.00, $legalMonetaryTotal->getTaxInclusiveAmount());
    }
}
