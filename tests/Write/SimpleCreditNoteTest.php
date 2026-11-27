<?php

namespace NumNum\UBL\Tests\Write;

use PHPUnit\Framework\TestCase;

/**
 * Test an UBL2.1 credit note document
 */
class SimpleCreditNoteTest extends TestCase
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
            ->setCreditNoteLines($creditNoteLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal)
            ->setInvoiceTypeCode(\NumNum\UBL\InvoiceTypeCode::CREDIT_NOTE);

        $creditNote->setTaxTotals([$taxTotal])
            ->setTaxCurrencyCode('CZK')
            ->addTaxTotal(
                (new \NumNum\UBL\TaxTotal())
                    ->setTaxAmount(52.5)
                    ->setCurrencyCode('CZK')
            );

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->creditNote($creditNote);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generate XML
        $dom = new \DOMDocument();
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/SimpleCreditNoteTest.xml');

        $this->assertEquals(true, $dom->schemaValidate($this->schema));

        $this->assertStringContainsString('<cbc:TaxCurrencyCode>CZK</cbc:TaxCurrencyCode>', $outputXMLString);
        $this->assertStringContainsString('<cbc:TaxAmount currencyID="EUR">2.10</cbc:TaxAmount>', $outputXMLString);
        $this->assertStringContainsString('<cbc:TaxAmount currencyID="CZK">52.50</cbc:TaxAmount>', $outputXMLString);

        $parsed = \NumNum\UBL\Reader::ubl()->parse($outputXMLString);
        $taxTotals = $parsed->getTaxTotals();

        $this->assertSame('CZK', $parsed->getTaxCurrencyCode());
        $this->assertCount(2, $taxTotals);
        $this->assertSame($taxTotals[0], $parsed->getTaxTotal());
        $this->assertSame(2.1, $taxTotals[0]->getTaxAmount());
        $this->assertSame('EUR', $taxTotals[0]->getCurrencyCode());
        $this->assertSame(52.5, $taxTotals[1]->getTaxAmount());
        $this->assertSame('CZK', $taxTotals[1]->getCurrencyCode());

        $creditNote->setTaxTotal($taxTotal)->setTaxCurrencyCode(null);
        $this->assertSame([$taxTotal], $creditNote->getTaxTotals());
        $this->assertStringNotContainsString('TaxCurrencyCode', $generator->creditNote($creditNote));
    }
}
