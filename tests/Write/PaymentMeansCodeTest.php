<?php

namespace NumNum\UBL\Tests\Write;

use DateTime;
use NumNum\UBL\AccountingParty;
use NumNum\UBL\Address;
use NumNum\UBL\Country;
use NumNum\UBL\Generator;
use NumNum\UBL\Invoice;
use NumNum\UBL\InvoiceLine;
use NumNum\UBL\Item;
use NumNum\UBL\LegalMonetaryTotal;
use NumNum\UBL\Party;
use NumNum\UBL\PaymentMeans;
use NumNum\UBL\Price;
use NumNum\UBL\UnitCode;
use PHPUnit\Framework\TestCase;

/**
 * Test writing a PaymentMeans that carries no payment means code
 */
class PaymentMeansCodeTest extends TestCase
{
    /** @test */
    public function testPaymentMeansCodeIsNotWrittenWhenNull()
    {
        $country = (new Country())
            ->setIdentificationCode('BE');

        $address = (new Address())
            ->setStreetName('Korenmarkt')
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        $invoice = (new Invoice())
            ->setId(1234)
            ->setIssueDate(new DateTime())
            ->setAccountingSupplierParty(
                (new AccountingParty())->setParty(
                    (new Party())->setName('Supplier')->setPostalAddress($address)
                )
            )
            ->setAccountingCustomerParty(
                (new AccountingParty())->setParty(
                    (new Party())->setName('Client')->setPostalAddress($address)
                )
            )
            ->setInvoiceLines([
                (new InvoiceLine())
                    ->setId(1)
                    ->setItem((new Item())->setName('Product Name'))
                    ->setPrice((new Price())->setPriceAmount(10)->setUnitCode(UnitCode::UNIT))
                    ->setLineExtensionAmount(10)
                    ->setInvoicedQuantity(1),
            ])
            ->setLegalMonetaryTotal(
                (new LegalMonetaryTotal())->setPayableAmount(12.1)->setTaxExclusiveAmount(10)
            )
            ->setPaymentMeans([
                (new PaymentMeans())->setPaymentMeansCode(null)->setPaymentId('INV-123'),
            ]);

        $xml = (new Generator())->invoice($invoice);

        // cbc:PaymentMeansCode is mandatory when present, so an empty element
        // would be invalid UBL. It has to be left out entirely instead.
        $this->assertStringNotContainsString('PaymentMeansCode', $xml);
        $this->assertStringContainsString('<cbc:PaymentID>INV-123</cbc:PaymentID>', $xml);
    }
}
