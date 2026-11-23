<?php

namespace NumNum\UBL\Tests\Write;

use DateTime;
use DOMDocument;
use NumNum\UBL\AccountingParty;
use NumNum\UBL\Address;
use NumNum\UBL\Country;
use NumNum\UBL\Generator;
use NumNum\UBL\Invoice;
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
 * Test Item with OriginCountry
 */
class ItemOriginCountryTest extends TestCase
{
    private string $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';

    /** @test */
    public function testItemWithOriginCountryIsValid()
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

        // Origin country for the product
        $originCountry = (new Country())
            ->setIdentificationCode('CN');

        // Product with OriginCountry
        $productItem = (new Item())
            ->setName('Product Name')
            ->setDescription('Product Description')
            ->setSellersItemIdentification('SELLERID')
            ->setOriginCountry($originCountry);

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
            ->setTaxTotal($taxTotal);

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Verify that the OriginCountry element is present in the XML
        $this->assertStringContainsString('<cac:OriginCountry>', $outputXMLString);
        $this->assertStringContainsString('<cbc:IdentificationCode>CN</cbc:IdentificationCode>', $outputXMLString);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generated XML
        $dom = new DOMDocument();
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/ItemOriginCountryTest.xml');

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }
}
