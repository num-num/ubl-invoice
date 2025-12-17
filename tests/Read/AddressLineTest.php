<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\Address;
use NumNum\UBL\AddressLine;
use NumNum\UBL\Invoice;
use PHPUnit\Framework\TestCase;

/**
 * Test AddressLine deserialization from UBL XML
 */
class AddressLineTest extends TestCase
{
    /** @test */
    public function testAddressLineDeserializesFromXml()
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

        $ublReader = \NumNum\UBL\Reader::ubl();
        $invoice = $ublReader->parse($xml);

        $this->assertInstanceOf(Invoice::class, $invoice);

        // Get the supplier's postal address
        $supplierParty = $invoice->getAccountingSupplierParty()->getParty();
        $address = $supplierParty->getPostalAddress();

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('Main Street 123', $address->getStreetName());
        $this->assertEquals('Building A', $address->getAdditionalStreetName());
        $this->assertEquals('Copenhagen', $address->getCityName());
        $this->assertEquals('1000', $address->getPostalZone());

        // Verify AddressLines were deserialized
        $addressLines = $address->getAddressLines();
        $this->assertIsArray($addressLines);
        $this->assertCount(2, $addressLines);

        $this->assertInstanceOf(AddressLine::class, $addressLines[0]);
        $this->assertEquals('3rd Floor', $addressLines[0]->getLine());

        $this->assertInstanceOf(AddressLine::class, $addressLines[1]);
        $this->assertEquals('Suite 5', $addressLines[1]->getLine());

        // Verify Country is still parsed correctly
        $this->assertNotNull($address->getCountry());
        $this->assertEquals('DK', $address->getCountry()->getIdentificationCode());

        // Verify customer address without AddressLines
        $customerAddress = $invoice->getAccountingCustomerParty()->getParty()->getPostalAddress();
        $this->assertEmpty($customerAddress->getAddressLines());
    }

    /** @test */
    public function testSingleAddressLineDeserializes()
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
            <cac:PostalAddress>
                <cbc:StreetName>Test Street</cbc:StreetName>
                <cbc:CityName>Test City</cbc:CityName>
                <cac:AddressLine>
                    <cbc:Line>Only One Line</cbc:Line>
                </cac:AddressLine>
                <cac:Country>
                    <cbc:IdentificationCode>BE</cbc:IdentificationCode>
                </cac:Country>
            </cac:PostalAddress>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PostalAddress>
                <cbc:StreetName>Customer Street</cbc:StreetName>
                <cbc:CityName>Customer City</cbc:CityName>
                <cac:Country>
                    <cbc:IdentificationCode>NL</cbc:IdentificationCode>
                </cac:Country>
            </cac:PostalAddress>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="EUR">0</cbc:TaxAmount>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:PayableAmount currencyID="EUR">50</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:InvoiceLine>
        <cbc:ID>1</cbc:ID>
        <cbc:InvoicedQuantity unitCode="C62">1</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="EUR">50</cbc:LineExtensionAmount>
    </cac:InvoiceLine>
</Invoice>
XML;

        $ublReader = \NumNum\UBL\Reader::ubl();
        $invoice = $ublReader->parse($xml);

        $address = $invoice->getAccountingSupplierParty()->getParty()->getPostalAddress();
        $addressLines = $address->getAddressLines();

        $this->assertCount(1, $addressLines);
        $this->assertEquals('Only One Line', $addressLines[0]->getLine());
    }
}

