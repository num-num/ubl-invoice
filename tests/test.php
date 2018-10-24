<?php
require_once "../vendor/autoload.php";

$xmlService = new Sabre\Xml\Service();

$xmlService->namespaceMap = [
    'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2' => '',
    'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' => 'cbc',
    'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' => 'cac'
];

$invoice = new \NumNum\UBL\Invoice\Invoice();
$date = \DateTime::createFromFormat('d-m-Y', '12-12-1994');
$invoice->setId('CIT1234');
$invoice->setIssueDate($date);
$invoice->setInvoiceTypeCode("SalesInvoice");

$accountingSupplierParty = new \NumNum\UBL\Invoice\Party();
$accountingSupplierParty->setName('CleverIt');
$supplierAddress = (new \NumNum\UBL\Invoice\Address())
    ->setCityName("Eindhoven")
    ->setStreetName("Keizersgracht")
    ->setBuildingNumber("15")
    ->setPostalZone("5600 AC")
    ->setCountry((new \NumNum\UBL\Invoice\Country())->setIdentificationCode("NL"));

$accountingSupplierParty->setPostalAddress($supplierAddress);
$accountingSupplierParty->setPhysicalLocation($supplierAddress);
$accountingSupplierParty->setContact((new \NumNum\UBL\Invoice\Contact())->setElectronicMail("info@cleverit.nl")->setTelephone("31402939003"));

$invoice->setAccountingSupplierParty($accountingSupplierParty);
$invoice->setAccountingCustomerParty($accountingSupplierParty);

$taxtotal = (new \NumNum\UBL\Invoice\TaxTotal())
    ->setTaxAmount(30)
    ->addTaxSubTotal((new \NumNum\UBL\Invoice\TaxSubTotal())
        ->setTaxAmount(21)
        ->setTaxableAmount(100)
        ->setTaxCategory((new \NumNum\UBL\Invoice\TaxCategory())
            ->setId("H")
            ->setName("NL, Hoog Tarief")
            ->setPercent(21.00)))
    ->addTaxSubTotal((new \NumNum\UBL\Invoice\TaxSubTotal())
        ->setTaxAmount(9)
        ->setTaxableAmount(100)
        ->setTaxCategory((new \NumNum\UBL\Invoice\TaxCategory())
            ->setId("X")
            ->setName("NL, Laag Tarief")
            ->setPercent(9.00)));

$invoiceLine = (new \NumNum\UBL\Invoice\InvoiceLine())
    ->setId(1)
    ->setInvoicedQuantity(1)
    ->setLineExtensionAmount(100)
    ->setTaxTotal($taxtotal)
    ->setItem((new \NumNum\UBL\Invoice\Item())->setName("Test item")->setDescription("test item description")->setSellersItemIdentification("1ABCD"));

$invoice->setInvoiceLines([$invoiceLine]);
$invoice->setTaxTotal($taxtotal);
$invoice->setLegalMonetaryTotal((new \NumNum\UBL\Invoice\LegalMonetaryTotal())
    ->setLineExtensionAmount(100)
    ->setTaxExclusiveAmount(100)
    ->setPayableAmount(-1000)
    ->setAllowanceTotalAmount(50));


file_put_contents("ubl.xml", \NumNum\UBL\Invoice\Generator::invoice($invoice, 'EUR'));
