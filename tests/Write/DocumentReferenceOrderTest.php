<?php

namespace NumNum\UBL\Tests\Write;

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
     * @param \NumNum\UBL\Invoice $document
     * @return \NumNum\UBL\Invoice
     */
    private function withAllDocumentReferences($document)
    {
        $country = (new \NumNum\UBL\Country())
            ->setIdentificationCode('BE');

        $address = (new \NumNum\UBL\Address())
            ->setStreetName('Korenmarkt')
            ->setCityName('Gent')
            ->setPostalZone('9000')
            ->setCountry($country);

        $supplierCompany = (new \NumNum\UBL\Party())
            ->setName('Supplier Company Name')
            ->setPostalAddress($address);

        $clientCompany = (new \NumNum\UBL\Party())
            ->setName('Client Company Name')
            ->setPostalAddress($address);

        $legalMonetaryTotal = (new \NumNum\UBL\LegalMonetaryTotal())
            ->setPayableAmount(12.1)
            ->setTaxExclusiveAmount(10);

        $price = (new \NumNum\UBL\Price())
            ->setPriceAmount(10)
            ->setUnitCode(\NumNum\UBL\UnitCode::UNIT);

        $item = (new \NumNum\UBL\Item())
            ->setName('Product Name');

        $invoiceLine = (new \NumNum\UBL\InvoiceLine())
            ->setId(1)
            ->setItem($item)
            ->setPrice($price)
            ->setLineExtensionAmount(10)
            ->setInvoicedQuantity(1);

        return $document
            ->setId(1234)
            ->setIssueDate(new \DateTime())
            ->setAccountingSupplierParty(
                (new \NumNum\UBL\AccountingParty())->setParty($supplierCompany)
            )
            ->setAccountingCustomerParty(
                (new \NumNum\UBL\AccountingParty())->setParty($clientCompany)
            )
            ->setInvoiceLines([$invoiceLine])
            ->setLegalMonetaryTotal($legalMonetaryTotal)
            ->setOrderReference((new \NumNum\UBL\OrderReference())->setId('ORD-1'))
            ->setBillingReference(
                (new \NumNum\UBL\BillingReference())->setInvoiceDocumentReference(
                    (new \NumNum\UBL\InvoiceDocumentReference())->setOriginalInvoiceId('PREC-1')
                )
            )
            ->setDespatchDocumentReference(
                (new \NumNum\UBL\DespatchDocumentReference())->setId('DESP-1')
            )
            ->setReceiptDocumentReference(
                (new \NumNum\UBL\ReceiptDocumentReference())->setId('RCPT-1')
            )
            ->setOriginatorDocumentReference(
                (new \NumNum\UBL\OriginatorDocumentReference())->setId('ORIG-1')
            )
            ->setContractDocumentReference(
                (new \NumNum\UBL\ContractDocumentReference())->setId('CTR-1')
            )
            ->setAdditionalDocumentReferences([
                (new \NumNum\UBL\AdditionalDocumentReference())->setId('ADD-1'),
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
        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $elements = [];

        foreach ($dom->documentElement->childNodes as $node) {
            if (!$node instanceof \DOMElement) {
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
        $invoice = $this->withAllDocumentReferences(new \NumNum\UBL\Invoice());
        $invoice->setProjectReference((new \NumNum\UBL\ProjectReference())->setId('PRJ-1'));

        $xml = (new \NumNum\UBL\Generator())->invoice($invoice);

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
        $creditNote = $this->withAllDocumentReferences(new \NumNum\UBL\CreditNote());

        $xml = (new \NumNum\UBL\Generator())->creditNote($creditNote);

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
        $debitNote = $this->withAllDocumentReferences(new \NumNum\UBL\DebitNote());

        $xml = (new \NumNum\UBL\Generator())->debitNote($debitNote);

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
        $invoice = $this->withAllDocumentReferences(new \NumNum\UBL\Invoice())
            ->setDespatchDocumentReference(null)
            ->setOriginatorDocumentReference(null)
            ->setAdditionalDocumentReferences([]);

        $xml = (new \NumNum\UBL\Generator())->invoice($invoice);

        $this->assertEquals([
            'OrderReference',
            'BillingReference',
            'ReceiptDocumentReference',
            'ContractDocumentReference',
        ], $this->referenceElementsIn($xml));
    }
}
