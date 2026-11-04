<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\Invoice;
use \NumNum\UBL\Reader as UBLReader;
use PHPUnit\Framework\TestCase;
/**
 * @description tests multiples notes on invoice
 */
class MultipleNotesTest extends TestCase
{
    /**
     * @return void
     */
    public function testMultipleNotes()
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
    <cbc:Note>#PMD#Pénalités de retard : taux égal à trois fois le taux d'intérêt légal.</cbc:Note>
    <cbc:Note>#PMT#En cas de retard de paiement, une indemnité forfaitaire de 40,00 € pour frais de recouvrement sera due (articles L.441-10 et D.441-5 du Code de commerce).</cbc:Note>
    <cbc:Note>#AAB#Pas d'escompte pour paiement anticipé.</cbc:Note>
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
        $ublReader = UBLReader::ubl();
        $invoice = $ublReader->parse($xml);
        $notes = $invoice->getNotes();
        $this->assertCount(3, $notes);
        $this->asserEquals("#PMD#Pénalités de retard : taux égal à trois fois le taux d'intérêt légal.",$invoice->getNote());
        $this->asserEquals(array_keys($invoice->getNotes()),[0,1,2]);
    }
}
