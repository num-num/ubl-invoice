<?php

namespace NumNum\UBL\Tests;

use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
	private $schema = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';

	/** @test */
	public function xml_should_be_valid()
	{
		$dom = new \DOMDocument;
		$dom->Load('tests/ubl.xml');

		$this->assertEquals(true, $dom->schemaValidate($this->schema));
	}
}
