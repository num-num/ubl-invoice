<?php

namespace NumNum\UBL\Tests\Write;

use DateTime;
use DOMDocument;
use NumNum\UBL\AccountingParty;
use NumNum\UBL\AdditionalDocumentReference;
use NumNum\UBL\Address;
use NumNum\UBL\Attachment;
use NumNum\UBL\Contact;
use NumNum\UBL\Country;
use NumNum\UBL\Generator;
use NumNum\UBL\Invoice;
use NumNum\UBL\InvoiceLine;
use NumNum\UBL\InvoicePeriod;
use NumNum\UBL\Item;
use NumNum\UBL\LegalMonetaryTotal;
use NumNum\UBL\Party;
use NumNum\UBL\Price;
use NumNum\UBL\TaxCategory;
use NumNum\UBL\TaxScheme;
use NumNum\UBL\TaxSubTotal;
use NumNum\UBL\TaxTotal;
use NumNum\UBL\UnitCode;
use PHPUnit\Framework\TestCase;

/**
 * Test an Attachment with only an external reference (no embedded binary object)
 */
class ExternalReferenceOnlyAttachmentTest extends TestCase
{
    private string $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';

    /** @test */
    public function testIfXMLIsValid()
    {
        // Address country
        $country = (new Country())
            ->setIdentificationCode('BE');

        // Full address
        $address = (new Address())
            ->setStreetName('Korenmarkt')
            ->setBuildingNumber(1)
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        // Supplier company node
        $supplierCompany = (new Party())
            ->setName('Supplier Company Name')
            ->setPhysicalLocation($address)
            ->setPostalAddress($address);

        // Client contact node
        $clientContact = (new Contact())
            ->setName('Client name')
            ->setElectronicMail('email@client.com')
            ->setTelephone('0032 472 123 456')
            ->setTelefax('0032 9 1234 567');

        // Client company node
        $clientCompany = (new Party())
            ->setName('My client')
            ->setPostalAddress($address)
            ->setContact($clientContact);

        $legalMonetaryTotal = (new LegalMonetaryTotal())
            ->setPayableAmount(10 + 2)
            ->setAllowanceTotalAmount(0);

        // Tax scheme
        $taxScheme = (new TaxScheme())
            ->setId(0);

        // Product
        $productItem = (new Item())
            ->setName('Product Name')
            ->setDescription('Product Description')
            ->setSellersItemIdentification('SELLERID');

        // Price
        $price = (new Price())
            ->setBaseQuantity(1)
            ->setUnitCode(UnitCode::UNIT)
            ->setPriceAmount(10);

        // Invoice Line tax totals
        $lineTaxTotal = (new TaxTotal())
            ->setTaxAmount(2.1);

        // InvoicePeriod
        $invoicePeriod = (new InvoicePeriod())
            ->setStartDate(new DateTime());

        // Invoice Line(s)
        $invoiceLines = [];

        $invoiceLines[] = (new InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setInvoicePeriod($invoicePeriod)
            ->setPrice($price)
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1);

        // Total Taxes
        $taxCategory = (new TaxCategory())
            ->setId(0)
            ->setName('VAT21%')
            ->setPercent(.21)
            ->setTaxScheme($taxScheme);

        $taxSubTotal = (new TaxSubTotal())
            ->setTaxableAmount(10)
            ->setTaxAmount(2.1)
            ->setTaxCategory($taxCategory);

        $taxTotal = (new TaxTotal())
            ->addTaxSubTotal($taxSubTotal)
            ->setTaxAmount(2.1);

        // Attachment with only external reference (no embedded binary object)
        $attachment = (new Attachment())
            ->setExternalReference('https://payment.quickpay.net/payments/abc123');

        $additionalDocumentReference = (new AdditionalDocumentReference())
            ->setId('PaymentLink')
            ->setDocumentDescription('Payment link for invoice')
            ->setAttachment($attachment);

        $accountingSupplierParty = (new AccountingParty())
            ->setParty($supplierCompany);

        $accountingCustomerParty = (new AccountingParty())
            ->setSupplierAssignedAccountId('10001')
            ->setParty($clientCompany);

        // Invoice object
        $invoice = (new Invoice())
            ->setId(1234)
            ->setCopyIndicator(false)
            ->setIssueDate(new DateTime())
            ->setAccountingSupplierParty($accountingSupplierParty)
            ->setAccountingCustomerParty($accountingCustomerParty)
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal)
            ->setAdditionalDocumentReference($additionalDocumentReference);

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generated XML
        $dom = new DOMDocument();
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/ExternalReferenceOnlyAttachmentTest.xml');

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }

    /** @test */
    public function testExternalReferenceOnlyDoesNotContainEmbeddedDocument()
    {
        // Attachment with only external reference (no embedded binary object)
        $attachment = (new Attachment())
            ->setExternalReference('https://payment.quickpay.net/payments/abc123');

        $additionalDocumentReference = (new AdditionalDocumentReference())
            ->setId('PaymentLink')
            ->setAttachment($attachment);

        // Generate just the AdditionalDocumentReference XML
        $generator = new Generator();

        // Create a minimal invoice to test the attachment serialization
        $country = (new Country())->setIdentificationCode('BE');
        $address = (new Address())
            ->setStreetName('Test')
            ->setCityName('Test')
            ->setCountry($country);

        $party = (new Party())
            ->setName('Test')
            ->setPostalAddress($address);

        $accountingParty = (new AccountingParty())
            ->setParty($party);

        $taxScheme = (new TaxScheme())->setId(0);
        $taxCategory = (new TaxCategory())
            ->setId(0)
            ->setPercent(0)
            ->setTaxScheme($taxScheme);
        $taxSubTotal = (new TaxSubTotal())
            ->setTaxableAmount(0)
            ->setTaxAmount(0)
            ->setTaxCategory($taxCategory);
        $taxTotal = (new TaxTotal())
            ->addTaxSubTotal($taxSubTotal)
            ->setTaxAmount(0);

        $item = (new Item())->setName('Test');
        $price = (new Price())->setPriceAmount(0);
        $invoiceLine = (new InvoiceLine())
            ->setId(1)
            ->setItem($item)
            ->setPrice($price)
            ->setInvoicedQuantity(1);

        $legalMonetaryTotal = (new LegalMonetaryTotal())
            ->setPayableAmount(0);

        $invoice = (new Invoice())
            ->setId(1)
            ->setIssueDate(new DateTime())
            ->setAccountingSupplierParty($accountingParty)
            ->setAccountingCustomerParty($accountingParty)
            ->setInvoiceLines([$invoiceLine])
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal)
            ->setAdditionalDocumentReference($additionalDocumentReference);

        $outputXMLString = $generator->invoice($invoice);

        // Assert that the XML contains ExternalReference
        $this->assertStringContainsString('ExternalReference', $outputXMLString);
        $this->assertStringContainsString(
            'https://payment.quickpay.net/payments/abc123',
            $outputXMLString
        );

        // Assert that the XML does NOT contain EmbeddedDocumentBinaryObject
        $this->assertStringNotContainsString('EmbeddedDocumentBinaryObject', $outputXMLString);
    }
}
