<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class PaymentTerms implements XmlSerializable
{
	private $paymentDueDate;

	/**
	 * @return mixed
	 */
	public function getPaymentDueDate()
	{
		return $this->paymentDueDate;
	}

	/**
	 * @param mixed $paymentDueDate
	 * @return int
	 */
	public function setPaymentDueDate(\DateTime $paymentDueDate)
	{
		$this->paymentDueDate = $paymentDueDate;
		return $this;
	}

	function xmlSerialize(Writer $writer)
	{
		if ($this->getPaymentDueDate() != null) {
			$writer->write([Schema::CBC . 'PaymentDueDate' => $this->getPaymentDueDate()->format('Y-m-d')]);
		}
	}
}
