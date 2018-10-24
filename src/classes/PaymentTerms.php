<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class PaymentTerms implements XmlSerializable
{
	private $note;

	/**
	 * @return mixed
	 */
	public function getNote()
	{
		return $this->note;
	}

	/**
	 * @param mixed $note
	 * @return PaymentTerms
	 */
	public function setNote($note)
	{
		$this->note = $note;
		return $this;
	}

	function xmlSerialize(Writer $writer)
	{
		$writer->write([ Schema::CBC . 'Note' => $this->note ]);
	}
}
