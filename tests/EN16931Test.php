<?php

namespace NumNum\UBL\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Test an UBL2.1 invoice document
 */
class EN16931Test extends TestCase
{
    private $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';
    private $xslfile = 'vendor/num-num/ubl-invoice/tests/EN16931-UBL-validation.xslt';

    /** @test */
    public function testIfXMLIsValid()
    {
        // Tax scheme
        $taxScheme = (new \NumNum\UBL\TaxScheme())
            ->setId('VAT');

        // Address country
        $country = (new \NumNum\UBL\Country())
            ->setIdentificationCode('BE');

        // Full address
        $address = (new \NumNum\UBL\Address())
            ->setStreetName('Korenmarkt 1')
            ->setAdditionalStreetName('Building A')
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        $financialInstitutionBranch = (new \NumNum\UBL\FinancialInstitutionBranch())
            ->setId('RABONL2U');

        $payeeFinancialAccount = (new \NumNum\UBL\PayeeFinancialAccount())
           ->setFinancialInstitutionBranch($financialInstitutionBranch)
            ->setName('Customer Account Holder')
            ->setId('NL00RABO0000000000');

        $paymentMeans = (new \NumNum\UBL\PaymentMeans())
            ->setPayeeFinancialAccount($payeeFinancialAccount)
            ->setPaymentMeansCode(31, [])
            ->setPaymentId('our invoice 1234');

        // Supplier company node
        $supplierLegalEntity = (new \NumNum\UBL\LegalEntity())
            ->setRegistrationName('Supplier Company Name')
            ->setCompanyId('BE123456789');

        $supplierPartyTaxScheme = (new \NumNum\UBL\PartyTaxScheme())
            ->setTaxScheme($taxScheme)
            ->setCompanyId('BE123456789');

        $supplierCompany = (new \NumNum\UBL\Party())
            ->setName('Supplier Company Name')
            ->setLegalEntity($supplierLegalEntity)
            ->setPartyTaxScheme($supplierPartyTaxScheme)
            ->setPartyIdentificationId('BE123456789')
            ->setPostalAddress($address);

        // Client company node
        $clientLegalEntity = (new \NumNum\UBL\LegalEntity())
            ->setRegistrationName('Client Company Name')
            ->setCompanyId('Client Company Registration');

        $clientPartyTaxScheme = (new \NumNum\UBL\PartyTaxScheme())
            ->setTaxScheme($taxScheme)
            ->setCompanyId('BE123456789');

        $clientCompany = (new \NumNum\UBL\Party())
            ->setName('Client Company Name')
            ->setLegalEntity($clientLegalEntity)
            ->setPartyTaxScheme($clientPartyTaxScheme)
            ->setPartyIdentificationId('BE123456789')
            ->setPostalAddress($address);

        $legalMonetaryTotal = (new \NumNum\UBL\LegalMonetaryTotal())
            ->setPayableAmount(10 + 2.1)
            ->setAllowanceTotalAmount(0)
            ->setTaxInclusiveAmount(10 + 2.1)
            ->setLineExtensionAmount(10)
            ->setTaxExclusiveAmount(10);

        $classifiedTaxCategory = (new \NumNum\UBL\ClassifiedTaxCategory())
            ->setId('S')
            ->setPercent(21.00)
            ->setTaxScheme($taxScheme);

        // Product
        $productItem = (new \NumNum\UBL\Item())
            ->setName('Product Name')
            ->setClassifiedTaxCategory($classifiedTaxCategory)
            ->setDescription('Product Description');

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
        $invoiceLine = (new \NumNum\UBL\InvoiceLine())
            ->setId(0)
            ->setItem($productItem)
            ->setPrice($price)
            ->setInvoicePeriod($invoicePeriod)
            ->setLineExtensionAmount(10)
            ->setInvoicedQuantity(1);

        $invoiceLines = [$invoiceLine];

        // Total Taxes
        $taxCategory = (new \NumNum\UBL\TaxCategory())
            ->setId('S', [])
            ->setPercent(21.00)
            ->setTaxScheme($taxScheme);

        $taxSubTotal = (new \NumNum\UBL\TaxSubTotal())
            ->setTaxableAmount(10)
            ->setTaxAmount(2.1)
            ->setTaxCategory($taxCategory);


        $taxTotal = (new \NumNum\UBL\TaxTotal())
            ->addTaxSubTotal($taxSubTotal)
            ->setTaxAmount(2.1);

        // Payment Terms
        $paymentTerms = (new \NumNum\UBL\PaymentTerms())
            ->setNote('30 days net');

        // Delivery
        $deliveryLocation = (new \NumNum\UBL\Address())
            ->setCountry($country);

        $delivery = (new \NumNum\UBL\Delivery())
            ->setActualDeliveryDate(new \DateTime())
            ->setDeliveryLocation($deliveryLocation);

        $orderReference = (new \NumNum\UBL\OrderReference())
            ->setId('5009567')
            ->setSalesOrderId('tRST-tKhM');

        // Invoice object
        $invoice = (new \NumNum\UBL\Invoice())
            ->setCustomizationID('urn:cen.eu:en16931:2017')
            ->setId(1234)
            ->setIssueDate(new \DateTime())
            ->setNote('invoice note')
            ->setDelivery($delivery)
            ->setAccountingSupplierParty($supplierCompany)
            ->setAccountingCustomerParty($clientCompany)
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setPaymentTerms($paymentTerms)
            ->setInvoicePeriod($invoicePeriod)
            ->setPaymentMeans($paymentMeans)
            ->setBuyerReference('BUYER_REF')
            ->setOrderReference($orderReference)
            ->setTaxTotal($taxTotal);

        // Test created object
        // Use \NumNum\UBL\Generator to generate an XML string
        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->invoice($invoice);

        // Create PHP Native DomDocument object, that can be
        // used to validate the generate XML
        $dom = new \DOMDocument;
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/EN16931Test.xml');

        // $this->assertEquals(true, $dom->schemaValidate($this->schema));

        // Use webservice at peppol.helger.com to verify the result
        $wsdl = "http://peppol.helger.com/wsdvs?wsdl=1";
        $client = new \SoapClient($wsdl);
        $response = $client->validate(['XML' => $outputXMLString, 'VESID' => 'eu.cen.en16931:ubl:1.3.1']);

        // Output validation warnings if present
        if ($response->mostSevereErrorLevel == 'WARN' && isset($response->Result[1]->Item)) {
            foreach ($response->Result[1]->Item as $responseWarning) {
                // fwrite(STDERR, '*** '.$responseWarning->errorText."\n");
                fwrite(STDERR, '*** '.json_encode($responseWarning)."\n");
            }
        }

        $this->assertEquals('SUCCESS', $response->mostSevereErrorLevel);
    }
}
