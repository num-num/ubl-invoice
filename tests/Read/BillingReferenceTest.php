<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\BillingReference;
use NumNum\UBL\Reader;
use PHPUnit\Framework\TestCase;

/**
 * Test reading one or more cac:BillingReference elements
 */
class BillingReferenceTest extends TestCase
{
    private function creditNoteXml(string $billingReferences): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2"
            xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
            xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:ID>CN-1</cbc:ID>
    <cbc:IssueDate>2026-11-01</cbc:IssueDate>
    <cbc:CreditNoteTypeCode>381</cbc:CreditNoteTypeCode>
    <cbc:DocumentCurrencyCode>EUR</cbc:DocumentCurrencyCode>
    {$billingReferences}
    <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PostalAddress>
                <cbc:CityName>Supplier City</cbc:CityName>
                <cac:Country>
                    <cbc:IdentificationCode>BE</cbc:IdentificationCode>
                </cac:Country>
            </cac:PostalAddress>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PostalAddress>
                <cbc:CityName>Customer City</cbc:CityName>
                <cac:Country>
                    <cbc:IdentificationCode>BE</cbc:IdentificationCode>
                </cac:Country>
            </cac:PostalAddress>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="EUR">0</cbc:TaxAmount>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:PayableAmount currencyID="EUR">10</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
</CreditNote>
XML;
    }

    private function billingReferenceXml(string $invoiceId, string $issueDate): string
    {
        return <<<XML
<cac:BillingReference>
        <cac:InvoiceDocumentReference>
            <cbc:ID>{$invoiceId}</cbc:ID>
            <cbc:IssueDate>{$issueDate}</cbc:IssueDate>
        </cac:InvoiceDocumentReference>
    </cac:BillingReference>
XML;
    }

    public function testMultipleBillingReferences()
    {
        $xml = $this->creditNoteXml(
            $this->billingReferenceXml('INV-1234', '2026-10-01')
            . $this->billingReferenceXml('INV-5678', '2026-10-15')
        );

        $creditNote = Reader::ubl()->parse($xml);

        $billingReferences = $creditNote->getBillingReferences();

        // Keys must start at 0, even though the BillingReference elements
        // are not the first children of the document
        $this->assertSame([0, 1], array_keys($billingReferences));
        $this->assertContainsOnlyInstancesOf(BillingReference::class, $billingReferences);
        $this->assertEquals(
            'INV-1234',
            $billingReferences[0]->getInvoiceDocumentReference()->getOriginalInvoiceId()
        );
        $this->assertEquals(
            'INV-5678',
            $billingReferences[1]->getInvoiceDocumentReference()->getOriginalInvoiceId()
        );

        // The single-reference getter keeps returning the first reference
        $this->assertSame($billingReferences[0], $creditNote->getBillingReference());
    }

    public function testSingleBillingReference()
    {
        $xml = $this->creditNoteXml($this->billingReferenceXml('INV-1234', '2026-10-01'));

        $creditNote = Reader::ubl()->parse($xml);

        $this->assertCount(1, $creditNote->getBillingReferences());
        $this->assertInstanceOf(BillingReference::class, $creditNote->getBillingReference());
        $this->assertEquals(
            'INV-1234',
            $creditNote->getBillingReference()->getInvoiceDocumentReference()->getOriginalInvoiceId()
        );
    }

    public function testNoBillingReference()
    {
        $creditNote = Reader::ubl()->parse($this->creditNoteXml(''));

        $this->assertNull($creditNote->getBillingReference());
        $this->assertSame([], $creditNote->getBillingReferences());
    }
}
