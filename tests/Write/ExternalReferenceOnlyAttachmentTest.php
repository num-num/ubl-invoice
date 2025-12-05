<?php

namespace NumNum\UBL\Tests\Write;

use PHPUnit\Framework\TestCase;

/**
 * Test an Attachment with only an external reference (no embedded binary object)
 */
class ExternalReferenceOnlyAttachmentTest extends TestCase
{
    private $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';

    /** @test */
    public function testIfXMLIsValid()
    {
        // Address country
        $country = (new \NumNum\UBL\Country())
            ->setIdentificationCode('BE');

        // Full address
        $address = (new \NumNum\UBL\Address())
            ->setStreetName('Korenmarkt')
            ->setBuildingNumber(1)
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        // Supplier company node
        $supplierCompany = (new \NumNum\UBL\Party())
            ->setName('Supplier Company Name')
            ->setPhysicalLocation($address)
            ->setPostalAddress($address);

        // Client contact node
        $clientContact = (new \NumNum\UBL\Contact())
            ->setName('Client name')
            ->setElectronicMail('email@client.com')
            ->setTelephone('0032 472 123 456')
            ->setTelefax('0032 9 1234 567');

        // Client company node
        $clientCompany = (new \NumNum\UBL\Party())
            ->setName('My client')
            ->setPostalAddress($address)
            ->setContact($clientContact);

        $legalMonetaryTotal = (new \NumNum\UBL\LegalMonetaryTotal())
            ->setPayableAmount(10 + 2)
            ->setAllowanceTotalAmount(0);

        // Tax scheme
        $taxScheme = (new \NumNum\UBL\TaxScheme())
            ->setId(0);

        // Product
        $productItem = (new \NumNum\UBL\Item())
            ->setName('Product Name')
            ->setDescription('Product Description')
            ->setSellersItemIdentification('SELLERID');

        // Price
        $price = (new \NumNum\UBL\Price())
            ->setBaseQuantity(1)
            ->setUnitCode(\NumNum\UBL\UnitCode::UNIT)
            ->setPriceAmount(10);

        // Invoice Line tax totals
        $lineTaxTotal = (new \NumNum\UBL\TaxTotal())
            ->setTaxAmount(2.1);

        // InvoicePeriod
        $invoicePeriod = (new \NumNum\UBL\InvoicePeriod())
            ->setStartDate(new \DateTime());

        // Invoice Line(s)
        $invoiceLines = [];

        $invoiceLines[] = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setInvoicePeriod($invoicePeriod)
            ->setPrice($price)
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1);

        // Total Taxes
        $taxCategory = (new \NumNum\UBL\TaxCategory())
            ->setId(0)
            ->setName('VAT21%')
            ->setPercent(.21)
            ->setTaxScheme($taxScheme);

        $taxSubTotal = (new \NumNum\UBL\TaxSubTotal())
            ->setTaxableAmount(10)
            ->setTaxAmount(2.1)
            ->setTaxCategory($taxCategory);

        $taxTotal = (new \NumNum\UBL\TaxTotal())
            ->addTaxSubTotal($taxSubTotal)
            ->setTaxAmount(2.1);

        // Attachment with only external reference (no embedded binary object)
        $attachment = (new \NumNum\UBL\Attachment())
            ->setExternalReference('https://payment.quickpay.net/payments/abc123');

        $additionalDocumentReference = (new \NumNum\UBL\AdditionalDocumentReference())
            ->setId('PaymentLink')
            ->setDocumentDescription('Payment link for invoice')
            ->setAttachment($attachment);

        $accountingSupplierParty = (new \NumNum\UBL\AccountingParty())
            ->setParty($supplierCompany);

        $accountingCustomerParty = (new \NumNum\UBL\AccountingParty())
            ->setSupplierAssignedAccountId('10001')
            ->setParty($clientCompany);

        // Invoice object
        $invoice = (new \NumNum\UBL\Invoice())
            ->setId(1234)
            ->setCopyIndicator(false)
            ->setIssueDate(new \DateTime())
            ->setAccountingSupplierParty($accountingSupplierParty)
            ->setAccountingCustomerParty($accountingCustomerParty)
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal)
            ->setAdditionalDocumentReference($additionalDocumentReference);

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generate XML
        $dom = new \DOMDocument();
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/ExternalReferenceOnlyAttachmentTest.xml');

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }

    /** @test */
    public function testExternalReferenceOnlyDoesNotContainEmbeddedDocument()
    {
        // Attachment with only external reference (no embedded binary object)
        $attachment = (new \NumNum\UBL\Attachment())
            ->setExternalReference('https://payment.quickpay.net/payments/abc123');

        $additionalDocumentReference = (new \NumNum\UBL\AdditionalDocumentReference())
            ->setId('PaymentLink')
            ->setAttachment($attachment);

        // Generate just the AdditionalDocumentReference XML
        $generator = new \NumNum\UBL\Generator();

        // Create a minimal invoice to test the attachment serialization
        $country = (new \NumNum\UBL\Country())->setIdentificationCode('BE');
        $address = (new \NumNum\UBL\Address())
            ->setStreetName('Test')
            ->setCityName('Test')
            ->setCountry($country);

        $party = (new \NumNum\UBL\Party())
            ->setName('Test')
            ->setPostalAddress($address);

        $accountingParty = (new \NumNum\UBL\AccountingParty())
            ->setParty($party);

        $taxScheme = (new \NumNum\UBL\TaxScheme())->setId(0);
        $taxCategory = (new \NumNum\UBL\TaxCategory())
            ->setId(0)
            ->setPercent(0)
            ->setTaxScheme($taxScheme);
        $taxSubTotal = (new \NumNum\UBL\TaxSubTotal())
            ->setTaxableAmount(0)
            ->setTaxAmount(0)
            ->setTaxCategory($taxCategory);
        $taxTotal = (new \NumNum\UBL\TaxTotal())
            ->addTaxSubTotal($taxSubTotal)
            ->setTaxAmount(0);

        $item = (new \NumNum\UBL\Item())->setName('Test');
        $price = (new \NumNum\UBL\Price())->setPriceAmount(0);
        $invoiceLine = (new \NumNum\UBL\InvoiceLine())
            ->setId(1)
            ->setItem($item)
            ->setPrice($price)
            ->setInvoicedQuantity(1);

        $legalMonetaryTotal = (new \NumNum\UBL\LegalMonetaryTotal())
            ->setPayableAmount(0);

        $invoice = (new \NumNum\UBL\Invoice())
            ->setId(1)
            ->setIssueDate(new \DateTime())
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

