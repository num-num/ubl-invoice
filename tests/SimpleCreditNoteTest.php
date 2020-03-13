<?php

namespace NumNum\UBL\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Test an UBL2.1 credit note document
 */
class SimpleCreditNoteTest extends TestCase
{
    private $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';

    /** @test */
    public function testIfXMLIsValid()
    {
        $address = new \NumNum\UBL\Address();
        $address->setStreetName('Korenmarkt');
        $address->setBuildingNumber(1);
        $address->setCityName('Gent');
        $address->setPostalZone('9000');

        // Address country
        $country = new \NumNum\UBL\Country();
        $country->setIdentificationCode('BE');
        $address->setCountry($country);

        // Supplier company node
        $supplierCompany  = new \NumNum\UBL\Party();
        $supplierCompany->setName('Supplier Company Name');
        $supplierCompany->setPhysicalLocation($address);
        $supplierCompany->setPostalAddress($address);

        // Client company node
        $clientCompany = new \NumNum\UBL\Party();
        $clientCompany->setName('My client');
        $clientCompany->setPostalAddress($address);

        $legalMonetaryTotal = new \NumNum\UBL\LegalMonetaryTotal();
        $legalMonetaryTotal->setPayableAmount(10 + 2);
        $legalMonetaryTotal->setAllowanceTotalAmount(0);

        // Tax scheme
        $taxScheme = new \NumNum\UBL\TaxScheme();
        $taxScheme->setId(0);

        // Product
        $productItem = new \NumNum\UBL\Item();
        $productItem->setName('Product Name');
        $productItem->setDescription('Product Description');

        // Price
        $price = new \NumNum\UBL\Price();
        $price->setBaseQuantity(1);
        $price->setUnitCode(\NumNum\UBL\UnitCode::UNIT);
        $price->setPriceAmount(10);

        // Invoice Line tax totals
        $lineTaxTotal = new \NumNum\UBL\TaxTotal();
        $lineTaxTotal->setTaxAmount(2.1);

        // Invoice Line(s)
        $invoiceLine = new \NumNum\UBL\InvoiceLine();
        $invoiceLine->setId(0);
        $invoiceLine->setItem($productItem);
        $invoiceLine->setPrice($price);
        $invoiceLine->setTaxTotal($lineTaxTotal);
        $invoiceLine->setInvoicedQuantity(1);

        $invoiceLines = [$invoiceLine];

        // Total Taxes
        $taxCategory = new \NumNum\UBL\TaxCategory();
        $taxCategory->setId(0);
        $taxCategory->setName('VAT21%');
        $taxCategory->setPercent(.21);
        $taxCategory->setTaxScheme($taxScheme);

        $taxSubTotal = new \NumNum\UBL\TaxSubTotal();
        $taxSubTotal->setTaxableAmount(10);
        $taxSubTotal->setTaxAmount(2.1);
        $taxSubTotal->setTaxCategory($taxCategory);

        $taxTotal = new \NumNum\UBL\TaxTotal();
        $taxTotal->addTaxSubTotal($taxSubTotal);
        $taxTotal->setTaxAmount(2.1);

        // Invoice object
        $invoice = new \NumNum\UBL\Invoice();
        $invoice->setId(1234);
        $invoice->setIssueDate(new \DateTime());
        $invoice->setInvoiceTypeCode('invoiceTypeCode');
        $invoice->setAccountingSupplierParty($supplierCompany);
        $invoice->setAccountingCustomerParty($clientCompany);
        $invoice->setInvoiceLines($invoiceLines);
        $invoice->setLegalMonetaryTotal($legalMonetaryTotal);
        $invoice->setTaxTotal($taxTotal);
        $invoice->setInvoiceTypeCode(\NumNum\UBL\InvoiceTypeCode::CREDIT_NOTE);

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generate XML
        $dom = new \DOMDocument;
        $dom->loadXML($outputXMLString);

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }
}
