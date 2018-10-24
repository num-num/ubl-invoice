<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class TaxScheme implements XmlSerializable
{
	private $id;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 * @return int
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	function xmlSerialize(Writer $writer)
	{
		$writer->write([
			Schema::CAC . 'ID' => $this->id
		]);
	}
}
