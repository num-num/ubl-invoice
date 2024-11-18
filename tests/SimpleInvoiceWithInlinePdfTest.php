<?php

namespace NumNum\UBL\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Test an UBL2.1 invoice document
 */
class SimpleInvoiceWithInlinePdfTest extends TestCase
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

        $orderLineReference = (new \NumNum\UBL\OrderLineReference)
            ->setLineId('#ABC123');

        $invoiceLines[] = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setInvoicePeriod($invoicePeriod)
            ->setPrice($price)
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1)
            ->setOrderLineReference($orderLineReference);

        $invoiceLines[] = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setInvoicePeriod($invoicePeriod)
            ->setPrice($price)
            ->setAccountingCost('Product 123')
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1)
            ->setOrderLineReference($orderLineReference);

        $invoiceLines[] = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setInvoicePeriod($invoicePeriod)
            ->setPrice($price)
            ->setAccountingCostCode('Product 123')
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1)
            ->setOrderLineReference($orderLineReference);


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

        // Example if you have some inline or in-memory filestream/file contents
        $fileStream = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'SampleInvoice.pdf'); // this would be your file contents

        $base64EncodedFileStream = base64_encode($fileStream);

        $attachment = (new \NumNum\UBL\Attachment())
            ->setFileStream($base64EncodedFileStream, 'Invoice.pdf', 'application/pdf');

        $additionalDocumentReference = (new \NumNum\UBL\AdditionalDocumentReference())
            ->setAttachment($attachment);

        // Invoice object
        $invoice = (new \NumNum\UBL\Invoice())
            ->setId(1234)
            ->setAdditionalDocumentReference($additionalDocumentReference)
            ->setCopyIndicator(false)
            ->setIssueDate(new \DateTime())
            ->setAccountingSupplierParty($supplierCompany)
            ->setAccountingCustomerParty($clientCompany)
            ->setSupplierAssignedAccountID('10001')
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal);

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generate XML
        $dom = new \DOMDocument;
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/SimpleInvoiceWithInlinePdfTest.xml');

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }
}
