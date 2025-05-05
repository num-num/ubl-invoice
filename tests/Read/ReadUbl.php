<?php

namespace NumNum\UBL\Tests\Read;

use NumNum\UBL\CreditNote;
use NumNum\UBL\Invoice;
use PHPUnit\Framework\TestCase;

class ReadUbl extends TestCase
{
    /**
     * @test
     * @dataProvider invoiceFileDataProvider
     */
    public function testIfInvoiceUblCanBeRead($fileName)
    {
        $ublReader = \NumNum\UBL\Reader::ubl();

        $invoice = $ublReader->parse(file_get_contents($fileName));

        $this->assertNotNull($invoice);
        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    /**
     * @test
     * @dataProvider creditNoteFileDataProvider
     */
    public function testIfCreditNoteUblCanBeRead($fileName)
    {
        $ublReader = \NumNum\UBL\Reader::ubl();

        $invoice = $ublReader->parse(file_get_contents($fileName));

        $this->assertNotNull($invoice);
        $this->assertInstanceOf(CreditNote::class, $invoice);
    }

    /**
     * Data provider for testIfInvoiceUblCanBeRead
     */
    public function invoiceFileDataProvider(): array
    {
        return [
            [dirname(__DIR__).'/Files/UBL-Invoice-2.1-Example.xml'],
            [dirname(__DIR__).'/Files/UBL-Invoice-2.0-Detached.xml'],
            [dirname(__DIR__).'/Files/ubl-invoice-simple.xml'],
        ];
    }

    /**
     * Data provider for testIfInvoiceUblCanBeRead
     */
    public function creditNoteFileDataProvider(): array
    {
        return [
            [dirname(__DIR__).'/Files/UBL-CreditNote.xml'],
        ];
    }
}
