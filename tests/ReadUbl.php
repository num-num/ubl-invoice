<?php

namespace NumNum\UBL\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Test an UBL2.1 invoice document
 */
class ReadUbl extends TestCase
{
    /** @test */
    public function testIfUblCanBeRead()
    {
        $invoiceReader = \NumNum\UBL\Reader::invoice();

        $xmlContents = file_get_contents(__DIR__ . '/ubl.xml');

        $invoice = $invoiceReader->parse($xmlContents);
    }
}
