<?php

namespace NumNum\UBL\Tests\Write;

use PHPUnit\Framework\TestCase;

/**
 * Test an UBL2.1 invoice document
 */
class MultiplePaymentMeansTest extends TestCase
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

        $invoiceLines[] = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setInvoicePeriod($invoicePeriod)
            ->setPrice($price)
            ->setAccountingCost('Product 123')
            ->setTaxTotal($lineTaxTotal)
            ->setInvoicedQuantity(1);

        $invoiceLines[] = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setInvoicePeriod($invoicePeriod)
            ->setPrice($price)
            ->setAccountingCostCode('Product 123')
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

        $paymentMeans = [];

        $payeeFinancialAccount = (new \NumNum\UBL\PayeeFinancialAccount())->setId('RO123456789012345');
        $paymentMeans[] = (new \NumNum\UBL\PaymentMeans())
            ->setPaymentMeansCode(31)
            ->setPaymentDueDate(new \DateTime())
            ->setPayeeFinancialAccount($payeeFinancialAccount);

        $payeeFinancialAccount = (new \NumNum\UBL\PayeeFinancialAccount())->setId('RO544456789067890');
        $paymentMeans[] = (new \NumNum\UBL\PaymentMeans())
            ->setPaymentMeansCode(31)
            ->setPaymentDueDate(new \DateTime())
            ->setPayeeFinancialAccount($payeeFinancialAccount);

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
            ->setPaymentMeans($paymentMeans)
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal);

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generate XML
        $dom = new \DOMDocument();
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/MultiplePaymentMeansTest.xml');

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }
}
