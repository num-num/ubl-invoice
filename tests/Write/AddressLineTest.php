<?php

namespace NumNum\UBL\Tests\Write;

use NumNum\UBL\Address;
use NumNum\UBL\AddressLine;
use NumNum\UBL\Country;
use NumNum\UBL\Generator;
use NumNum\UBL\Invoice;
use NumNum\UBL\AccountingParty;
use NumNum\UBL\InvoiceLine;
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
 * Test AddressLine serialization in UBL2.1 invoice
 */
class AddressLineTest extends TestCase
{
    private $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';

    /** @test */
    public function testAddressLineSerializesToXml()
    {
        // Address country
        $country = (new Country())
            ->setIdentificationCode('DK');

        // Full address with AddressLines
        $address = (new Address())
            ->setStreetName('Main Street 123')
            ->setAdditionalStreetName('Building A')
            ->addAddressLine((new AddressLine())->setLine('3rd Floor'))
            ->addAddressLine((new AddressLine())->setLine('Suite 5'))
            ->setCityName('Copenhagen')
            ->setPostalZone('1000')
            ->setCountry($country);

        // Supplier company node
        $supplierCompany = (new Party())
            ->setName('Supplier Company Name')
            ->setPostalAddress($address);

        // Client company node
        $clientCompany = (new Party())
            ->setName('My client')
            ->setPostalAddress($address);

        $legalMonetaryTotal = (new LegalMonetaryTotal())
            ->setPayableAmount(10 + 2)
            ->setAllowanceTotalAmount(0);

        // Tax scheme
        $taxScheme = (new TaxScheme())
            ->setId(0);

        // Product
        $productItem = (new Item())
            ->setName('Product Name')
            ->setDescription('Product Description');

        // Price
        $price = (new Price())
            ->setBaseQuantity(1)
            ->setUnitCode(UnitCode::UNIT)
            ->setPriceAmount(10);

        // Invoice Line tax totals
        $lineTaxTotal = (new TaxTotal())
            ->setTaxAmount(2.1);

        // Invoice Line(s)
        $invoiceLines = [];
        $invoiceLines[] = (new InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
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

        $accountingSupplierParty = (new AccountingParty())
            ->setParty($supplierCompany);

        $accountingCustomerParty = (new AccountingParty())
            ->setParty($clientCompany);

        // Invoice object
        $invoice = (new Invoice())
            ->setId(1234)
            ->setCopyIndicator(false)
            ->setIssueDate(new \DateTime())
            ->setAccountingSupplierParty($accountingSupplierParty)
            ->setAccountingCustomerParty($accountingCustomerParty)
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal);

        // Generate XML
        $generator = new Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Verify AddressLine elements are present in XML
        $this->assertStringContainsString('<cac:AddressLine>', $outputXMLString);
        $this->assertStringContainsString('<cbc:Line>3rd Floor</cbc:Line>', $outputXMLString);
        $this->assertStringContainsString('<cbc:Line>Suite 5</cbc:Line>', $outputXMLString);

        // Create PHP Native DomDocument object for validation
        $dom = new \DOMDocument();
        $dom->loadXML($outputXMLString);

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }

    /** @test */
    public function testAddressWithoutAddressLinesIsValid()
    {
        // Address country
        $country = (new Country())
            ->setIdentificationCode('BE');

        // Address without AddressLines
        $address = (new Address())
            ->setStreetName('Korenmarkt')
            ->setBuildingNumber(1)
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        // Verify addressLines is empty array
        $this->assertIsArray($address->getAddressLines());
        $this->assertEmpty($address->getAddressLines());

        // Supplier company node
        $supplierCompany = (new Party())
            ->setName('Supplier Company Name')
            ->setPostalAddress($address);

        // Client company node
        $clientCompany = (new Party())
            ->setName('My client')
            ->setPostalAddress($address);

        $legalMonetaryTotal = (new LegalMonetaryTotal())
            ->setPayableAmount(12)
            ->setAllowanceTotalAmount(0);

        // Tax scheme
        $taxScheme = (new TaxScheme())
            ->setId(0);

        // Product
        $productItem = (new Item())
            ->setName('Product Name');

        // Price
        $price = (new Price())
            ->setBaseQuantity(1)
            ->setUnitCode(UnitCode::UNIT)
            ->setPriceAmount(10);

        // Invoice Line tax totals
        $lineTaxTotal = (new TaxTotal())
            ->setTaxAmount(2);

        // Invoice Line(s)
        $invoiceLines = [];
        $invoiceLines[] = (new InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setPrice($price)
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1);

        // Total Taxes
        $taxCategory = (new TaxCategory())
            ->setId(0)
            ->setPercent(.20)
            ->setTaxScheme($taxScheme);

        $taxSubTotal = (new TaxSubTotal())
            ->setTaxableAmount(10)
            ->setTaxAmount(2)
            ->setTaxCategory($taxCategory);

        $taxTotal = (new TaxTotal())
            ->addTaxSubTotal($taxSubTotal)
            ->setTaxAmount(2);

        $accountingSupplierParty = (new AccountingParty())
            ->setParty($supplierCompany);

        $accountingCustomerParty = (new AccountingParty())
            ->setParty($clientCompany);

        // Invoice object
        $invoice = (new Invoice())
            ->setId(1234)
            ->setCopyIndicator(false)
            ->setIssueDate(new \DateTime())
            ->setAccountingSupplierParty($accountingSupplierParty)
            ->setAccountingCustomerParty($accountingCustomerParty)
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal);

        // Generate XML
        $generator = new Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Verify no AddressLine elements
        $this->assertStringNotContainsString('<cac:AddressLine>', $outputXMLString);

        // Create PHP Native DomDocument object for validation
        $dom = new \DOMDocument();
        $dom->loadXML($outputXMLString);

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }

    /** @test */
    public function testSetAddressLinesReplacesExisting()
    {
        $address = (new Address())
            ->addAddressLine((new AddressLine())->setLine('First'))
            ->addAddressLine((new AddressLine())->setLine('Second'));

        $this->assertCount(2, $address->getAddressLines());

        // Replace with new array
        $address->setAddressLines([
            (new AddressLine())->setLine('New Line')
        ]);

        $this->assertCount(1, $address->getAddressLines());
        $this->assertEquals('New Line', $address->getAddressLines()[0]->getLine());
    }
}

