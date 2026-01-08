<?php

namespace NumNum\UBL\Tests\Write;

use PHPUnit\Framework\TestCase;

/**
 * Test an UBL2.1 invoice document
 */
class ExtensionTest extends TestCase
{
    private $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';

    /** @test */
    public function testIfXMLIsValid()
    {
        $country = (new \NumNum\UBL\Country())
            ->setIdentificationCode('BE');

        $address = (new \NumNum\UBL\Address())
            ->setStreetName('Korenmarkt')
            ->setBuildingNumber(1)
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        $supplierCompany = (new \NumNum\UBL\Party())
            ->setName('Supplier Company Name')
            ->setPhysicalLocation($address)
            ->setPostalAddress($address);

        $clientContact = (new \NumNum\UBL\Contact())
            ->setName('Client name')
            ->setElectronicMail('email@client.com')
            ->setTelephone('0032 472 123 456')
            ->setTelefax('0032 9 1234 567');

        $clientCompany = (new \NumNum\UBL\Party())
            ->setName('My client')
            ->setPostalAddress($address)
            ->setContact($clientContact);

        $legalMonetaryTotal = (new \NumNum\UBL\LegalMonetaryTotal())
            ->setPayableAmount(10 + 2)
            ->setAllowanceTotalAmount(0);

        $taxScheme = (new \NumNum\UBL\TaxScheme())
            ->setId(0);

        $productItem = (new \NumNum\UBL\Item())
            ->setName('Product Name')
            ->setDescription('Product Description')
            ->setSellersItemIdentification('SELLERID');

        $price = (new \NumNum\UBL\Price())
            ->setBaseQuantity(1)
            ->setUnitCode(\NumNum\UBL\UnitCode::UNIT)
            ->setPriceAmount(10);

        $lineTaxTotal = (new \NumNum\UBL\TaxTotal())
            ->setTaxAmount(2.5);

        $invoicePeriod = (new \NumNum\UBL\InvoicePeriod())
            ->setStartDate(new \DateTime());

        $invoiceLines = [];

        $orderLineReference = (new \NumNum\UBL\OrderLineReference())
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

        $taxCategory = (new \NumNum\UBL\TaxCategory())
            ->setId(0)
            ->setName('VAT25%')
            ->setPercent(.25)
            ->setTaxScheme($taxScheme);

        $taxSubTotal = (new \NumNum\UBL\TaxSubTotal())
            ->setTaxableAmount(10)
            ->setTaxAmount(2.5)
            ->setTaxCategory($taxCategory);

        $taxTotal = (new \NumNum\UBL\TaxTotal())
            ->addTaxSubTotal($taxSubTotal)
            ->setTaxAmount(2.5);

        $accountingSupplierParty = (new \NumNum\UBL\AccountingParty())
            ->setParty($supplierCompany);

        $accountingCustomerParty = (new \NumNum\UBL\AccountingParty())
            ->setSupplierAssignedAccountId('10001')
            ->setParty($clientCompany);

        $extension = (new \NumNum\UBL\Extension())
            ->setContent([
                'hrextac:HRFISK20Data' => [
                    'hrextac:HRTaxTotal' => [
                        [
                            'name' => 'cbc:TaxAmount',
                            'value' => 2.5,
                            'attributes' => [
                                'currencyID' => 'EUR'
                            ]
                        ],
                        'hrextac:HRTaxSubtotal' => [
                            [
                                [
                                    'name' => 'cbc:TaxableAmount',
                                    'value' => 10,
                                    'attributes' => [
                                        'currencyID' => 'EUR'
                                    ]
                                ],
                                [
                                    'name' => 'cbc:TaxAmount',
                                    'value' => 2.5,
                                    'attributes' => [
                                        'currencyID' => 'EUR'
                                    ]
                                ],
                                'hrextac:HRTaxCategory' => [
                                    'cbc:ID' => 'S',
                                    'cbc:Name' => 'HR:PDV25',
                                    'cbc:Percent' => '25',
                                    'hrextac:HRTaxScheme' => [
                                        'cbc:ID' => 'VAT'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'hrextac:HRLegalMonetaryTotal' => [
                        [
                            'name' => 'cbc:TaxExclusiveAmount',
                            'value' => 10,
                            'attributes' => [
                                'currencyID' => 'EUR'
                            ]
                        ],
                        [
                            'name' => 'hrextac:OutOfScopeOfVATAmount',
                            'value' => 0,
                            'attributes' => [
                                'currencyID' => 'EUR'
                            ]
                        ]
                    ]
                ]
            ])
            ->setAttributes([
                'xmlns:cct' => 'urn:un:unece:uncefact:data:specification:CoreComponentTypeSchemaModule:2',
                'xmlns:p3' => 'urn:oasis:names:specification:ubl:schema:xsd:UnqualifiedDataTypes-2',
                'xmlns:xsi' => 'https://www.w3.org/2001/XMLSchema-instance'
            ]);

        $invoice = (new \NumNum\UBL\Invoice())
            ->setId(1234)
            ->setCopyIndicator(false)
            ->setIssueDate(new \DateTime())
            ->setIssueTime(new \DateTime())
            ->setAccountingSupplierParty($accountingSupplierParty)
            ->setAccountingCustomerParty($accountingCustomerParty)
            ->setInvoiceLines($invoiceLines)
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setTaxTotal($taxTotal)
            ->addExtension($extension);

        $generator = new \NumNum\UBL\Generator();
        $outputXMLString = $generator->invoice($invoice, 'EUR', [
            'urn:mfin.gov.hr:schema:xsd:HRExtensionAggregateComponents-1' => 'hrextac'
        ]);

        $dom = new \DOMDocument();
        $dom->loadXML($outputXMLString);

        $dom->save('./tests/ExtensionTest.xml');

        $this->assertEquals(true, $dom->schemaValidate($this->schema));
    }
}
