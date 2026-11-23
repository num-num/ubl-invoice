<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\Address;
use NumNum\UBL\Invoice;
use NumNum\UBL\Party;
use NumNum\UBL\Reader;
use PHPUnit\Framework\TestCase;

/**
 * Test Delivery deserialization from UBL XML
 */
class DeliveryTest extends TestCase
{
    /** @test */
    public function testDeliveryPartyDeserializesIntoAParty()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:ID>123</cbc:ID>
    <cbc:IssueDate>2024-01-01</cbc:IssueDate>
    <cbc:InvoiceTypeCode>380</cbc:InvoiceTypeCode>
    <cbc:DocumentCurrencyCode>EUR</cbc:DocumentCurrencyCode>
    <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PostalAddress>
                <cbc:CityName>Gent</cbc:CityName>
                <cac:Country>
                    <cbc:IdentificationCode>BE</cbc:IdentificationCode>
                </cac:Country>
            </cac:PostalAddress>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PostalAddress>
                <cbc:CityName>Amsterdam</cbc:CityName>
                <cac:Country>
                    <cbc:IdentificationCode>NL</cbc:IdentificationCode>
                </cac:Country>
            </cac:PostalAddress>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:Delivery>
        <cbc:ActualDeliveryDate>2024-02-02</cbc:ActualDeliveryDate>
        <cac:DeliveryLocation>
            <cac:Address>
                <cbc:CityName>Brugge</cbc:CityName>
                <cac:Country>
                    <cbc:IdentificationCode>BE</cbc:IdentificationCode>
                </cac:Country>
            </cac:Address>
        </cac:DeliveryLocation>
        <cac:DeliveryParty>
            <cac:PartyName>
                <cbc:Name>DelivCo</cbc:Name>
            </cac:PartyName>
        </cac:DeliveryParty>
    </cac:Delivery>
    <cac:LegalMonetaryTotal>
        <cbc:PayableAmount currencyID="EUR">12.10</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:InvoiceLine>
        <cbc:ID>1</cbc:ID>
        <cbc:InvoicedQuantity unitCode="C62">1</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="EUR">10</cbc:LineExtensionAmount>
    </cac:InvoiceLine>
</Invoice>
XML;

        $invoice = Reader::ubl()->parse($xml);

        $this->assertInstanceOf(Invoice::class, $invoice);

        $delivery = $invoice->getDelivery();

        $this->assertEquals('2024-02-02', $delivery->getActualDeliveryDate()->format('Y-m-d'));

        // cac:DeliveryParty is a PartyType, so it hydrates into a Party. Peppol
        // BIS 3.0 allows it a cac:PartyName only, the address of the delivery
        // being carried by cac:DeliveryLocation instead.
        $deliveryParty = $delivery->getDeliveryParty();

        $this->assertInstanceOf(Party::class, $deliveryParty);
        $this->assertEquals('DelivCo', $deliveryParty->getName());

        $deliveryLocation = $delivery->getDeliveryLocation();

        $this->assertInstanceOf(Address::class, $deliveryLocation);
        $this->assertEquals('Brugge', $deliveryLocation->getCityName());
    }
}
