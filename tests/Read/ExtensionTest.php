<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\Extension;
use NumNum\UBL\Invoice;
use NumNum\UBL\Reader;
use NumNum\UBL\Schema;
use PHPUnit\Framework\TestCase;

/**
 * Test UBLExtensions deserialization from UBL XML
 */
class ExtensionTest extends TestCase
{
    private const HR_NAMESPACE = 'urn:mfin.gov.hr:schema:xsd:HRExtensionAggregateComponents-1';

    /** @test */
    public function testInvoiceWithExtensionsCanBeRead()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
         xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
         xmlns:hrextac="urn:mfin.gov.hr:schema:xsd:HRExtensionAggregateComponents-1">
    <ext:UBLExtensions>
        <ext:UBLExtension id="hr-fisk"
                          xmlns:cct="urn:un:unece:uncefact:data:specification:CoreComponentTypeSchemaModule:2">
            <ext:ExtensionContent id="content">
                <hrextac:HRFISK20Data>
                    <hrextac:HRLegalMonetaryTotal>
                        <cbc:TaxExclusiveAmount currencyID="EUR">100.00</cbc:TaxExclusiveAmount>
                        <hrextac:OutOfScopeOfVATAmount currencyID="EUR">0.00</hrextac:OutOfScopeOfVATAmount>
                    </hrextac:HRLegalMonetaryTotal>
                </hrextac:HRFISK20Data>
            </ext:ExtensionContent>
        </ext:UBLExtension>
        <ext:UBLExtension>
            <ext:ExtensionContent>
                <hrextac:Other>second</hrextac:Other>
            </ext:ExtensionContent>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:ID>INV-123</cbc:ID>
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
        <cbc:TaxAmount currencyID="EUR">25.00</cbc:TaxAmount>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:PayableAmount currencyID="EUR">125.00</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
</Invoice>
XML;

        /** @var Invoice $invoice */
        $invoice = Reader::ubl()->parse($xml);

        $this->assertInstanceOf(Invoice::class, $invoice);

        // The fields around the extensions must still be read
        $this->assertEquals('INV-123', $invoice->getId());
        $this->assertEquals('2024-01-01', $invoice->getIssueDate()->format('Y-m-d'));
        $this->assertEquals('Supplier', $invoice->getAccountingSupplierParty()->getParty()->getName());
        $this->assertEquals(125.00, $invoice->getLegalMonetaryTotal()->getPayableAmount());

        $extensions = $invoice->getExtensions();
        $this->assertCount(2, $extensions);
        $this->assertContainsOnlyInstancesOf(Extension::class, $extensions);

        $this->assertEquals(['id' => 'hr-fisk'], $extensions[0]->getAttributes());
        $this->assertEquals([], $extensions[1]->getAttributes());

        // The content is the inner tree of ExtensionContent
        $content = $extensions[0]->getContent();
        $this->assertEquals('{' . self::HR_NAMESPACE . '}HRFISK20Data', $content[0]['name']);

        $legalMonetaryTotal = $content[0]['value'][0];
        $this->assertEquals('{' . self::HR_NAMESPACE . '}HRLegalMonetaryTotal', $legalMonetaryTotal['name']);
        $this->assertEquals(Schema::CBC . 'TaxExclusiveAmount', $legalMonetaryTotal['value'][0]['name']);
        $this->assertEquals('100.00', $legalMonetaryTotal['value'][0]['value']);
        $this->assertEquals(['currencyID' => 'EUR'], $legalMonetaryTotal['value'][0]['attributes']);

        $this->assertEquals('{' . self::HR_NAMESPACE . '}Other', $extensions[1]->getContent()[0]['name']);
        $this->assertEquals('second', $extensions[1]->getContent()[0]['value']);
    }

    /** @test */
    public function testInvoiceWithoutExtensionsHasNoExtensions()
    {
        /** @var Invoice $invoice */
        $invoice = Reader::ubl()->parse(file_get_contents(dirname(__DIR__) . '/Files/ubl-invoice-simple.xml'));

        $this->assertSame([], $invoice->getExtensions());
    }

    /** @test */
    public function testExtensionsSurviveAWriteReadRoundTrip()
    {
        $extension = (new Extension())
            ->setContent([
                'hrextac:HRFISK20Data' => [
                    'hrextac:HRLegalMonetaryTotal' => [
                        [
                            'name' => 'cbc:TaxExclusiveAmount',
                            'value' => 10,
                            'attributes' => [
                                'currencyID' => 'EUR'
                            ]
                        ],
                    ]
                ]
            ])
            ->setAttributes([
                'id' => 'hr-fisk',
            ]);

        $written = $this->writeInvoiceWithExtension($extension);

        /** @var Invoice $invoice */
        $invoice = Reader::ubl()->parse($written);

        $this->assertCount(1, $invoice->getExtensions());
        $readExtension = $invoice->getExtensions()[0];
        $this->assertInstanceOf(Extension::class, $readExtension);
        $this->assertEquals($extension->getAttributes(), $readExtension->getAttributes());
        $this->assertEquals('1234', $invoice->getId());

        $rewritten = $this->writeInvoiceWithExtension($readExtension);

        $this->assertEquals(
            $this->extractUblExtensions($written),
            $this->extractUblExtensions($rewritten)
        );
    }

    private function writeInvoiceWithExtension(Extension $extension): string
    {
        $address = (new \NumNum\UBL\Address())
            ->setStreetName('Korenmarkt')
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry((new \NumNum\UBL\Country())->setIdentificationCode('BE'));

        $party = (new \NumNum\UBL\Party())
            ->setName('Company')
            ->setPostalAddress($address);

        $invoice = (new Invoice())
            ->setId(1234)
            ->setIssueDate(new \DateTime('2024-01-01'))
            ->setAccountingSupplierParty((new \NumNum\UBL\AccountingParty())->setParty($party))
            ->setAccountingCustomerParty((new \NumNum\UBL\AccountingParty())->setParty($party))
            ->setInvoiceLines([
                (new \NumNum\UBL\InvoiceLine())
                    ->setId(1)
                    ->setItem((new \NumNum\UBL\Item())->setName('Product'))
                    ->setPrice((new \NumNum\UBL\Price())->setPriceAmount(10))
                    ->setInvoicedQuantity(1)
            ])
            ->setTaxTotal((new \NumNum\UBL\TaxTotal())->setTaxAmount(0))
            ->setLegalMonetaryTotal((new \NumNum\UBL\LegalMonetaryTotal())->setPayableAmount(10))
            ->addExtension($extension);

        return (new \NumNum\UBL\Generator())->invoice($invoice, 'EUR', [
            self::HR_NAMESPACE => 'hrextac'
        ]);
    }

    private function extractUblExtensions(string $xml): string
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml);

        return $dom
            ->getElementsByTagNameNS(trim(Schema::EXT, '{}'), 'UBLExtensions')
            ->item(0)
            ->C14N();
    }
}
