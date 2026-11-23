<?php

declare(strict_types=1);

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\Invoice;
use NumNum\UBL\PaymentMandate;
use PHPUnit\Framework\TestCase;

final class PaymentMandateTest extends TestCase
{
    public function testThatItCanReadPaymentMandate(): void
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
        xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
        xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2">
     <cbc:UBLVersionID>2.2</cbc:UBLVersionID>
     <cbc:ID>1234</cbc:ID>
     <cbc:IssueDate>2026-10-21</cbc:IssueDate>
     <cbc:InvoiceTypeCode>380</cbc:InvoiceTypeCode>
     <cbc:DocumentCurrencyCode>EUR</cbc:DocumentCurrencyCode>
     <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PartyName>
                <cbc:Name>Supplier</cbc:Name>
            </cac:PartyName>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PartyName>
                <cbc:Name>Customer</cbc:Name>
            </cac:PartyName>
        </cac:Party>
    </cac:AccountingCustomerParty>
     <cac:PaymentMeans>
      <cbc:PaymentMeansCode listID="UN/ECE 4461"
        listName="Payment Means"
        listURI="http://docs.oasis-open.org/ubl/os-UBL-2.0-update/cl/gc/default/PaymentMeansCode-2.0.gc"
        >1</cbc:PaymentMeansCode>
      <cac:PaymentMandate>
       <cbc:ID>12345</cbc:ID>
       <cac:PayerFinancialAccount>
        <cbc:ID>DE94201202005884225336</cbc:ID>
       </cac:PayerFinancialAccount>
      </cac:PaymentMandate>
     </cac:PaymentMeans>
         <cac:TaxTotal>
        <cbc:TaxAmount currencyID="EUR">0</cbc:TaxAmount>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:PayableAmount currencyID="EUR">100</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:InvoiceLine>
        <cbc:ID>1</cbc:ID>
        <cbc:InvoicedQuantity unitCode="C62">1</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="EUR">100</cbc:LineExtensionAmount>
    </cac:InvoiceLine>
    </Invoice>
XML;
        $ublReader = \NumNum\UBL\Reader::ubl();
        $invoice = $ublReader->parse($xml);

        $this->assertNotNull($invoice);
        $this->assertInstanceOf(Invoice::class, $invoice);

        $paymentMeans = $invoice->getPaymentMeans();
        $this->assertCount(1, $paymentMeans);

        $paymentMandate = array_values($paymentMeans)[0]->getPaymentMandate();
        $this->assertInstanceOf(PaymentMandate::class, $paymentMandate);
        $this->assertSame('12345', $paymentMandate->getId());

        $payerFinancialAccount = $paymentMandate->getPayerFinancialAccount();
        $this->assertSame('DE94201202005884225336', $payerFinancialAccount->getId());
    }
}
