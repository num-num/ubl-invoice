<?php

namespace NumNum\UBL\Tests\Write;

use DateTime;
use DOMDocument;
use NumNum\UBL\AccountingParty;
use NumNum\UBL\Address;
use NumNum\UBL\ClassifiedTaxCategory;
use NumNum\UBL\Country;
use NumNum\UBL\Delivery;
use NumNum\UBL\FinancialInstitutionBranch;
use NumNum\UBL\Generator;
use NumNum\UBL\Invoice;
use NumNum\UBL\InvoiceLine;
use NumNum\UBL\InvoicePeriod;
use NumNum\UBL\Item;
use NumNum\UBL\LegalEntity;
use NumNum\UBL\LegalMonetaryTotal;
use NumNum\UBL\OrderReference;
use NumNum\UBL\Party;
use NumNum\UBL\PartyTaxScheme;
use NumNum\UBL\PayeeFinancialAccount;
use NumNum\UBL\PaymentMeans;
use NumNum\UBL\PaymentTerms;
use NumNum\UBL\Price;
use NumNum\UBL\ProjectReference;
use NumNum\UBL\TaxCategory;
use NumNum\UBL\TaxScheme;
use NumNum\UBL\TaxSubTotal;
use NumNum\UBL\TaxTotal;
use NumNum\UBL\UNCL4461;
use NumNum\UBL\UnitCode;
use PHPUnit\Framework\TestCase;

/**
 * Test an UBL2.1 invoice document
 */
class ProjectReferenceTest extends TestCase
{
    private string $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';

    /** @test */
    public function testIfXMLIsValid()
    {
        // Tax scheme
        $taxScheme = (new TaxScheme())
            ->setId('VAT');

        // Address country
        $country = (new Country())
            ->setIdentificationCode('BE');

        // Full address
        $address = (new Address())
            ->setStreetName('Korenmarkt 1')
            ->setAdditionalStreetName('Building A')
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        $financialInstitutionBranch = (new FinancialInstitutionBranch())
            ->setId('RABONL2U');

        $payeeFinancialAccount = (new PayeeFinancialAccount())
           ->setFinancialInstitutionBranch($financialInstitutionBranch)
            ->setName('Customer Account Holder')
            ->setId('NL00RABO0000000000');

        $paymentMeans = (new PaymentMeans())
            ->setPayeeFinancialAccount($payeeFinancialAccount)
            ->setPaymentMeansCode(UNCL4461::DEBIT_TRANSFER, [])
            ->setPaymentId('our invoice 1234');

        // Supplier company node
        $supplierLegalEntity = (new LegalEntity())
            ->setRegistrationName('Supplier Company Name')
            ->setCompanyId('BE123456789');

        $supplierPartyTaxScheme = (new PartyTaxScheme())
            ->setTaxScheme($taxScheme)
            ->setCompanyId('BE123456789');

        $supplierCompany = (new Party())
            ->setName('Supplier Company Name')
            ->setLegalEntity($supplierLegalEntity)
            ->setPartyTaxScheme($supplierPartyTaxScheme)
            ->setPartyIdentificationId('BE123456789')
            ->setPostalAddress($address);

        // Client company node
        $clientLegalEntity = (new LegalEntity())
            ->setRegistrationName('Client Company Name')
            ->setCompanyId('Client Company Registration');

        $clientPartyTaxScheme = (new PartyTaxScheme())
            ->setTaxScheme($taxScheme)
            ->setCompanyId('BE123456789');

        $clientCompany = (new Party())
            ->setName('Client Company Name')
            ->setLegalEntity($clientLegalEntity)
            ->setPartyTaxScheme($clientPartyTaxScheme)
            ->setPartyIdentificationId('BE123456789')
            ->setPostalAddress($address);

        $legalMonetaryTotal = (new LegalMonetaryTotal())
            ->setPayableAmount(10 + 2.1)
            ->setAllowanceTotalAmount(0)
            ->setTaxInclusiveAmount(10 + 2.1)
            ->setLineExtensionAmount(10)
            ->setTaxExclusiveAmount(10);

        $classifiedTaxCategory = (new ClassifiedTaxCategory())
            ->setId('S')
            ->setPercent(21.00)
            ->setTaxScheme($taxScheme);

        // Product
        $productItem = (new Item())
            ->setName('Product Name')
            ->setClassifiedTaxCategory($classifiedTaxCategory)
            ->setDescription('Product Description');

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
        $invoiceLine = (new InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setPrice($price)
            ->setInvoicePeriod($invoicePeriod)
            ->setLineExtensionAmount(10)
            ->setInvoicedQuantity(1);

        $invoiceLines = [$invoiceLine];

        // Total Taxes
        $taxCategory = (new TaxCategory())
            ->setId('S', [])
            ->setPercent(21.00)
            ->setTaxScheme($taxScheme);

        $taxSubTotal = (new TaxSubTotal())
            ->setTaxableAmount(10)
            ->setTaxAmount(2.1)
            ->setTaxCategory($taxCategory);


        $taxTotal = (new TaxTotal())
            ->addTaxSubTotal($taxSubTotal)
            ->setTaxAmount(2.1);

        // Payment Terms
        $paymentTerms = (new PaymentTerms())
            ->setNote('30 days net');

        // Delivery
        $deliveryLocation = (new Address())
            ->setCountry($country);

        $delivery = (new Delivery())
            ->setActualDeliveryDate(new DateTime())
            ->setDeliveryLocation($deliveryLocation);

        $orderReference = (new OrderReference())
            ->setId('5009567')
            ->setSalesOrderId('tRST-tKhM');

        // Test Project Reference
        $projectReference = (new ProjectReference())
            ->setId('Project1234');

        $accountingSupplierParty = (new AccountingParty())
            ->setParty($supplierCompany);

        $accountingCustomerParty = (new AccountingParty())
            ->setParty($clientCompany);

        // Invoice object
        $invoice = (new Invoice())
            ->setCustomizationID('urn:cen.eu:en16931:2017')
            ->setId(1234)
            ->setIssueDate(new DateTime())
            ->setNote('invoice note')
            ->setDelivery($delivery)
            ->setAccountingSupplierParty($accountingSupplierParty)
            ->setAccountingCustomerParty($accountingCustomerParty)
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setPaymentTerms($paymentTerms)
            ->setInvoicePeriod($invoicePeriod)
            ->setPaymentMeans([$paymentMeans])
            ->setBuyerReference('BUYER_REF')
            ->setOrderReference($orderReference)
            ->setTaxTotal($taxTotal)
            ->setProjectReference($projectReference);

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generated XML
        $dom = new DOMDocument;
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/ProjectReferenceTest.xml');

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }
}
