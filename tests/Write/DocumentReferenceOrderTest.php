<?php

namespace NumNum\UBL\Tests\Write;

use DateTime;
use DOMDocument;
use DOMElement;
use NumNum\UBL\AccountingParty;
use NumNum\UBL\AdditionalDocumentReference;
use NumNum\UBL\Address;
use NumNum\UBL\BillingReference;
use NumNum\UBL\ContractDocumentReference;
use NumNum\UBL\Country;
use NumNum\UBL\CreditNote;
use NumNum\UBL\DebitNote;
use NumNum\UBL\DespatchDocumentReference;
use NumNum\UBL\Generator;
use NumNum\UBL\Invoice;
use NumNum\UBL\InvoiceDocumentReference;
use NumNum\UBL\InvoiceLine;
use NumNum\UBL\Item;
use NumNum\UBL\LegalMonetaryTotal;
use NumNum\UBL\OrderReference;
use NumNum\UBL\OriginatorDocumentReference;
use NumNum\UBL\Party;
use NumNum\UBL\Price;
use NumNum\UBL\ProjectReference;
use NumNum\UBL\ReceiptDocumentReference;
use NumNum\UBL\UnitCode;
use PHPUnit\Framework\TestCase;

/**
 * UBL 2.1 declares the document reference elements in an xsd:sequence, and the
 * required order differs per document type. Writing them in any other order
 * produces a document that fails schema validation.
 */
class DocumentReferenceOrderTest extends TestCase
{
    /**
     * Every document reference element this library can write, applied to a
     * document that is otherwise minimal but complete.
     *
     * @param Invoice $document
     * @return Invoice
     */
    private function withAllDocumentReferences(Invoice $document): Invoice
    {
        $country = (new Country())
            ->setIdentificationCode('BE');

        $address = (new Address())
            ->setStreetName('Korenmarkt')
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        $supplierCompany = (new Party())
            ->setName('Supplier Company Name')
            ->setPostalAddress($address);

        $clientCompany = (new Party())
            ->setName('Client Company Name')
            ->setPostalAddress($address);

        $legalMonetaryTotal = (new LegalMonetaryTotal())
            ->setPayableAmount(12.1)
            ->setTaxExclusiveAmount(10);

        $price = (new Price())
            ->setPriceAmount(10)
            ->setUnitCode(UnitCode::UNIT);

        $item = (new Item())
            ->setName('Product Name');

        $invoiceLine = (new InvoiceLine())
            ->setId(1)
            ->setItem($item)
            ->setPrice($price)
            ->setLineExtensionAmount(10)
            ->setInvoicedQuantity(1);

        return $document
            ->setId(1234)
            ->setIssueDate(new DateTime())
            ->setAccountingSupplierParty(
                (new AccountingParty())->setParty($supplierCompany)
            )
            ->setAccountingCustomerParty(
                (new AccountingParty())->setParty($clientCompany)
            )
            ->setInvoiceLines([$invoiceLine])
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setOrderReference((new OrderReference())->setId('ORD-1'))
            ->setBillingReference(
                (new BillingReference())->setInvoiceDocumentReference(
                    (new InvoiceDocumentReference())->setOriginalInvoiceId('PREC-1')
                )
            )
            ->setDespatchDocumentReference(
                (new DespatchDocumentReference())->setId('DESP-1')
            )
            ->setReceiptDocumentReference(
                (new ReceiptDocumentReference())->setId('RCPT-1')
            )
            ->setOriginatorDocumentReference(
                (new OriginatorDocumentReference())->setId('ORIG-1')
            )
            ->setContractDocumentReference(
                (new ContractDocumentReference())->setId('CTR-1')
            )
            ->setAdditionalDocumentReferences([
                (new AdditionalDocumentReference())->setId('ADD-1'),
            ]);
    }

    /**
     * The names of the direct child elements of the document that carry a
     * reference, in the order they were written.
     *
     * @param string $xml
     * @return string[]
     */
    private function referenceElementsIn(string $xml): array
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);

        $elements = [];

        foreach ($dom->documentElement->childNodes as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }

            if (substr($node->localName, -9) === 'Reference') {
                $elements[] = $node->localName;
            }
        }

        return $elements;
    }

    /** @test */
    public function testInvoiceWritesDocumentReferencesInSchemaOrder()
    {
        $invoice = $this->withAllDocumentReferences(new Invoice());
        $invoice->setProjectReference((new ProjectReference())->setId('PRJ-1'));

        $xml = (new Generator())->invoice($invoice);

        $this->assertEquals([
            'OrderReference',
            'BillingReference',
            'DespatchDocumentReference',
            'ReceiptDocumentReference',
            'OriginatorDocumentReference',
            'ContractDocumentReference',
            'AdditionalDocumentReference',
            'ProjectReference',
        ], $this->referenceElementsIn($xml));
    }

    /** @test */
    public function testCreditNoteWritesDocumentReferencesInSchemaOrder()
    {
        $creditNote = $this->withAllDocumentReferences(new CreditNote());

        $xml = (new Generator())->creditNote($creditNote);

        // A CreditNote puts ContractDocumentReference before, and
        // OriginatorDocumentReference after, AdditionalDocumentReference.
        $this->assertEquals([
            'OrderReference',
            'BillingReference',
            'DespatchDocumentReference',
            'ReceiptDocumentReference',
            'ContractDocumentReference',
            'AdditionalDocumentReference',
            'OriginatorDocumentReference',
        ], $this->referenceElementsIn($xml));
    }

    /** @test */
    public function testDebitNoteWritesDocumentReferencesInSchemaOrder()
    {
        $debitNote = $this->withAllDocumentReferences(new DebitNote());

        $xml = (new Generator())->debitNote($debitNote);

        $this->assertEquals([
            'OrderReference',
            'BillingReference',
            'DespatchDocumentReference',
            'ReceiptDocumentReference',
            'ContractDocumentReference',
            'AdditionalDocumentReference',
            'OriginatorDocumentReference',
        ], $this->referenceElementsIn($xml));
    }

    /** @test */
    public function testOmittedDocumentReferencesAreNotWritten()
    {
        $invoice = $this->withAllDocumentReferences(new Invoice())
            ->setDespatchDocumentReference(null)
            ->setOriginatorDocumentReference(null)
            ->setAdditionalDocumentReferences([]);

        $xml = (new Generator())->invoice($invoice);

        $this->assertEquals([
            'OrderReference',
            'BillingReference',
            'ReceiptDocumentReference',
            'ContractDocumentReference',
        ], $this->referenceElementsIn($xml));
    }
}
