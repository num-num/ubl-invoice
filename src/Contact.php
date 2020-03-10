<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Contact implements XmlSerializable
{
	private $telephone;
	private $telefax;
	private $electronicMail;

	/**
	 * @return mixed
	 */
	public function getTelephone()
	{
		return $this->telephone;
	}

	/**
	 * @param mixed $telephone
	 * @return Contact
	 */
	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTelefax()
	{
		return $this->telefax;
	}

	/**
	 * @param mixed $telefax
	 * @return Contact
	 */
	public function setTelefax($telefax)
	{
		$this->telefax = $telefax;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getElectronicMail()
	{
		return $this->electronicMail;
	}

	/**
	 * @param mixed $electronicMail
	 * @return Contact
	 */
	public function setElectronicMail($electronicMail)
	{
		$this->electronicMail = $electronicMail;
		return $this;
	}

	/**
	 * The xmlSerialize method is called during xml writing.
	 *
	 * @param Writer $writer
	 * @return void
	 */
	function xmlSerialize(Writer $writer)
	{
		if ($this->telephone !== null) {
			$writer->write([
				Schema::CBC . 'Telephone' => $this->telephone
			]);
		}

		if ($this->telefax !== null) {
			$writer->write([
				Schema::CBC . 'Telefax' => $this->telefax
			]);
		}

		if ($this->electronicMail !== null) {
			$writer->write([
				Schema::CBC . 'ElectronicMail' => $this->electronicMail
			]);
		}
	}
}
