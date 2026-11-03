<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\Invoice;
use NumNum\UBL\PayeeParty;
use PHPUnit\Framework\TestCase;

/**
 * Test PayeeParty deserialization from UBL XML
 */
class PayeePartyTest extends TestCase
{
    /** @test */
    public function testPayeePartyDeserializesFromXml()
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
    <cac:PayeeParty>
        <cac:PartyIdentification>
            <cbc:ID schemeID="GLN">098740918237</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyName>
            <cbc:Name>Ebeneser Scrooge Inc.</cbc:Name>
        </cac:PartyName>
        <cac:PartyLegalEntity>
            <cbc:CompanyID schemeID="UK:CH">6411982340</cbc:CompanyID>
        </cac:PartyLegalEntity>
    </cac:PayeeParty>
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

        // Get the payee party
        $payeeParty = $invoice->getPayeeParty();

        $this->assertInstanceOf(PayeeParty::class, $payeeParty);

        // Get the payee party identification
        $identification = $payeeParty->getPartyIdentificationId();
        $identificationScheme = $payeeParty->getPartyIdentificationSchemeId();

        $this->assertEquals('098740918237', $identification);
        $this->assertEquals('GLN', $identificationScheme);

        // Get the payee party name
        $partyName = $payeeParty->getPartyName();

        $this->assertEquals('Ebeneser Scrooge Inc.', $partyName);

        // Get the payee party legal entity company
        $legalEntityCompany = $payeeParty->getPartyLegalEntityCompanyId();
        $legalEntityCompanyScheme = $payeeParty->getPartyLegalEntityCompanySchemeId();

        $this->assertEquals('6411982340', $legalEntityCompany);
        $this->assertEquals('UK:CH', $legalEntityCompanyScheme);
    }
}
