<?php

namespace NumNum\UBL\Tests\Write;

use PHPUnit\Framework\TestCase;

/**
 * Test an UBL2.1 credit note document
 */
class BillingReferenceCreditNoteTest extends TestCase
{
    private $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-CreditNote-2.1.xsd';

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
            ->setDescription('Product Description')
            ->setSellersItemIdentification('SELLERID')
            ->setBuyersItemIdentification('BUYERID');

        // Price
        $price = (new \NumNum\UBL\Price())
            ->setBaseQuantity(1)
            ->setUnitCode(\NumNum\UBL\UnitCode::UNIT)
            ->setPriceAmount(10);

        // Invoice Line tax totals
        $lineTaxTotal = (new \NumNum\UBL\TaxTotal())
            ->setTaxAmount(2.1);

        // Invoice Line(s)
        $creditNoteLine = (new \NumNum\UBL\CreditNoteLine())
            ->setId(0)
            ->setItem($productItem)
            ->setPrice($price)
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1);

        $creditNoteLines = [$creditNoteLine];

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

        $billingReference = (new \NumNum\UBL\BillingReference())
            ->setInvoiceDocumentReference((new \NumNum\UBL\InvoiceDocumentReference())
                    ->setOriginalInvoiceId(1234)
                    ->setIssueDate(new \DateTime()));

        $accountingSupplierParty = (new \NumNum\UBL\AccountingParty())
            ->setParty($supplierCompany);

        $accountingCustomerParty = (new \NumNum\UBL\AccountingParty())
            ->setParty($clientCompany);

        // Invoice object
        $creditNote = (new \NumNum\UBL\CreditNote())
            ->setId(1234)
            ->setCopyIndicator(false)
            ->setIssueDate(new \DateTime())
            ->setAccountingSupplierParty($accountingSupplierParty)
            ->setAccountingCustomerParty($accountingCustomerParty)
            ->setBillingReference($billingReference)
            ->setCreditNoteLines($creditNoteLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal)
            ->setInvoiceTypeCode(\NumNum\UBL\InvoiceTypeCode::CREDIT_NOTE);

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->creditNote($creditNote);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generate XML
        $dom = new \DOMDocument();
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/BillingReferenceTest.xml');

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }
}
