<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\Invoice;
use NumNum\UBL\PaymentMeans;
use NumNum\UBL\Reader;
use PHPUnit\Framework\TestCase;

/**
 * Test PaymentMeans deserialization from UBL XML
 */
class PaymentMeansTest extends TestCase
{
    /** @test */
    public function testPaymentMeansCodeCanBeSetToNull()
    {
        $paymentMeans = (new PaymentMeans())->setPaymentMeansCode(null);

        $this->assertNull($paymentMeans->getPaymentMeansCode());
    }

    /** @test */
    public function testPaymentMeansWithoutCodeCanBeRead()
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
                <cbc:StreetName>Main Street 123</cbc:StreetName>
                <cbc:AdditionalStreetName>Building A</cbc:AdditionalStreetName>
                <cbc:CityName>Copenhagen</cbc:CityName>
                <cbc:PostalZone>1000</cbc:PostalZone>
                <cac:AddressLine>
                    <cbc:Line>3rd Floor</cbc:Line>
                </cac:AddressLine>
                <cac:AddressLine>
                    <cbc:Line>Suite 5</cbc:Line>
                </cac:AddressLine>
                <cac:Country>
                    <cbc:IdentificationCode>DK</cbc:IdentificationCode>
                </cac:Country>
            </cac:PostalAddress>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PostalAddress>
                <cbc:StreetName>Customer Street</cbc:StreetName>
                <cbc:CityName>Amsterdam</cbc:CityName>
                <cbc:PostalZone>1000</cbc:PostalZone>
                <cac:Country>
                    <cbc:IdentificationCode>NL</cbc:IdentificationCode>
                </cac:Country>
            </cac:PostalAddress>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:PaymentMeans>
        <cbc:PaymentID>INV-123</cbc:PaymentID>
    </cac:PaymentMeans>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="EUR">0</cbc:TaxAmount>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:PayableAmount currencyID="EUR">100</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:InvoiceLine>
        <cbc:ID>1</cbc:ID>
        <cbc:InvoicedQuantity unitCode="C62">1</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="EUR">100</cbc:LineExtensionAmount>
    </cac:InvoiceLine>
</Invoice>
XML;

        $invoice = Reader::ubl()->parse($xml);

        $this->assertInstanceOf(Invoice::class, $invoice);

        $paymentMeans = $invoice->getPaymentMeans();

        $this->assertCount(1, $paymentMeans);

        $first = reset($paymentMeans);

        $this->assertInstanceOf(PaymentMeans::class, $first);
        $this->assertNull($first->getPaymentMeansCode());
        $this->assertEquals('INV-123', $first->getPaymentId());
    }
}
