<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\CreditNote;
use NumNum\UBL\Invoice;
use NumNum\UBL\Reader;
use PHPUnit\Framework\TestCase;

/**
 * Test that the unitCode attribute of the quantity element is deserialized
 */
class InvoiceLineUnitCodeTest extends TestCase
{
    /** @test */
    public function testInvoiceLineUnitCodeDeserializesFromTheQuantityAttribute()
    {
        $invoice = Reader::ubl()->parse($this->documentXml('Invoice', 'InvoicedQuantity'));

        $this->assertInstanceOf(Invoice::class, $invoice);

        $lines = $invoice->getInvoiceLines();
        $line = reset($lines);

        $this->assertEquals(2.0, $line->getInvoicedQuantity());
        $this->assertEquals('HUR', $line->getUnitCode());
        $this->assertEquals('UNECERec20', $line->getUnitCodeListId());
    }

    /** @test */
    public function testCreditNoteLineUnitCodeDeserializesFromTheQuantityAttribute()
    {
        $creditNote = Reader::ubl()->parse($this->documentXml('CreditNote', 'CreditedQuantity'));

        $this->assertInstanceOf(CreditNote::class, $creditNote);

        $lines = $creditNote->getInvoiceLines();
        $line = reset($lines);

        $this->assertEquals(2.0, $line->getCreditedQuantity());
        $this->assertEquals('HUR', $line->getUnitCode());
        $this->assertEquals('UNECERec20', $line->getUnitCodeListId());
    }

    /**
     * A complete minimal document whose line quantity carries unit code attributes.
     *
     * @param string $rootTag
     * @param string $quantityTag
     * @return string
     */
    private function documentXml(string $rootTag, string $quantityTag): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<{$rootTag} xmlns="urn:oasis:names:specification:ubl:schema:xsd:{$rootTag}-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:ID>123</cbc:ID>
    <cbc:IssueDate>2024-01-01</cbc:IssueDate>
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
        <cbc:PayableAmount currencyID="EUR">121.00</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:{$rootTag}Line>
        <cbc:ID>1</cbc:ID>
        <cbc:{$quantityTag} unitCode="HUR" unitCodeListID="UNECERec20">2</cbc:{$quantityTag}>
        <cbc:LineExtensionAmount currencyID="EUR">100.00</cbc:LineExtensionAmount>
    </cac:{$rootTag}Line>
</{$rootTag}>
XML;
    }
}
