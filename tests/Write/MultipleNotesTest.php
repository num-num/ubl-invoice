<?php

namespace NumNum\UBL\Tests\Write;

use PHPUnit\Framework\TestCase;

class MultipleNotesTest extends TestCase
{
    private $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.2/xsd/maindoc/UBL-Invoice-2.2.xsd';

    public function testIfXMLIsValid()
    {
        // Address country
        $country = (new \NumNum\UBL\Country())
            ->setIdentificationCode('FR');

        // Full address
        $address = (new \NumNum\UBL\Address())
            ->setStreetName('Avenue des champs Élysées')
            ->setBuildingNumber(1)
            ->setCityName('Paris')
            ->setPostalZone('75008')
            ->setCountry($country);

        // Supplier company node
        $supplierCompany = (new \NumNum\UBL\Party())
            ->setName('Supplier Company Name')
            ->setPhysicalLocation($address)
            ->setPostalAddress($address)
            ->setPartyIdentificationSchemeName("SomeScheme");

        // Client company node
        $clientCompany = (new \NumNum\UBL\Party())
            ->setName('My client')
            ->setPostalAddress($address);

        $legalMonetaryTotal = (new \NumNum\UBL\LegalMonetaryTotal())
            ->setPayableAmount(10 + 2)
            ->setAllowanceTotalAmount(0);

        // Tax scheme
        $taxScheme = (new \NumNum\UBL\TaxScheme())
            ->setId(0);

        // Product
        $productItem = (new \NumNum\UBL\Item())
            ->setName('Product Name')
            ->setDescription('Product Description');

        // Price
        $price = (new \NumNum\UBL\Price())
            ->setBaseQuantity(1)
            ->setUnitCode(\NumNum\UBL\UnitCode::UNIT)
            ->setPriceAmount(10);

        // Invoice Line tax totals
        $lineTaxTotal = (new \NumNum\UBL\TaxTotal())
            ->setTaxAmount(2.1);

        // Invoice Line(s)
        $invoiceLine = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setPrice($price)
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1);

        $invoiceLines = [$invoiceLine];

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

        $contractDocumentReference = (new \NumNum\UBL\ContractDocumentReference())
            ->setId("123Test");

        $invoicePeriod = (new \NumNum\UBL\InvoicePeriod())
            ->setStartDate(new \DateTime('-31 days'))
            ->setEndDate(new \DateTime());

        $accountingSupplierParty = (new \NumNum\UBL\AccountingParty())
            ->setParty($supplierCompany);

        $accountingCustomerParty = (new \NumNum\UBL\AccountingParty())
            ->setParty($clientCompany);

        // Invoice object
        $invoice = (new \NumNum\UBL\Invoice())
            ->setUBLVersionId('2.2')
            ->setId(1234)
            ->setCopyIndicator(false)
            ->setIssueDate(new \DateTime())
            ->setInvoiceTypeCode(\NumNum\UBL\InvoiceTypeCode::INVOICE)
            ->setDueDate(new \DateTime())
            ->setAccountingSupplierParty($accountingSupplierParty)
            ->setAccountingCustomerParty($accountingCustomerParty)
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal)
            ->setContractDocumentReference($contractDocumentReference)
            ->setBuyerReference("SomeReference")
            ->setInvoicePeriod($invoicePeriod)
            ->addNote('#PMT#…')->addNote('#PMD#…')->addNote('#AAB#…');

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generate XML
        $dom = new \DOMDocument();
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/MultipleNotesTest.xml');
        $this->assertEquals(count($invoice->getNotes()),3);
        $this->assertEquals(count($invoice->getNote()),1);
        $this->assertEquals($invoice->getNote(),'#PMT#…');
        $this->assertEquals(['#PMT#…','#PMD#…','#AAB#…'],$invoice->getNotes());

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }
}
