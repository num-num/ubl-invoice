<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class LegalEntity implements XmlSerializable
{
	private $registrationName;
	private $companyId;

	public function getRegistrationName()
	{
		return $this->registrationName;
	}

	public function setRegistrationName($registrationName)
	{
		$this->registrationName = $registrationName;
	}

	public function getCompanyId()
	{
		return $this->companyId;
	}

	public function setCompanyId($companyId)
	{
		$this->companyId = $companyId;
	}

	/**
	 * The xmlSerialize method is called during xml writing.
	 *
	 * @param Writer $writer
	 * @return void
	 */
	function xmlSerialize(Writer $writer)
	{
		$writer->write([
			Schema::CBC . 'RegistrationName' => $this->registrationName,
			Schema::CBC . 'CompanyID' => $this->companyId
		]);
	}
}
