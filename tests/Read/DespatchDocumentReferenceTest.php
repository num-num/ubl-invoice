<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\DespatchDocumentReference;
use NumNum\UBL\Invoice;
use PHPUnit\Framework\TestCase;

class DespatchDocumentReferenceTest extends TestCase
{
    /**
     * @test
     */
    public function testDespatchDocumentReferenceCanBeRead()
    {
        $ublReader = \NumNum\UBL\Reader::ubl();

        $invoice = $ublReader->parse(file_get_contents(dirname(__DIR__).'/Files/DespatchDocumentReferenceTest.xml'));

        $this->assertNotNull($invoice);
        $this->assertInstanceOf(Invoice::class, $invoice);

        $despatchDocumentReference = $invoice->getDespatchDocumentReference();
        $this->assertNotNull($despatchDocumentReference);
        $this->assertInstanceOf(DespatchDocumentReference::class, $despatchDocumentReference);
        $this->assertEquals('DESP-2024-001', $despatchDocumentReference->getId());
    }
}

