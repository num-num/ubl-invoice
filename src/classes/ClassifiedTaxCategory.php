<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class ClassifiedTaxCategory implements XmlSerializable
{
	private $id;
	private $name;
	private $percent;
	private $taxScheme;

	public const UNCL5305 = 'UNCL5305';

	/**
	 * @return mixed
	 */
	public function getId()
	{
		if (!empty($this->id)) {
			return $this->id;
		}

		if ($this->getPercent() !== null) {
			if ($this->getPercent() == 21) {
				return 'S';
			} else if ($this->getPercent() == 12 || $this->getPercent() == 6) {
				return 'AA';
			} else if ($this->getPercent() == 0) {
				return 'Z';
			}
		}

		return null;
	}

	/**
	 * @param mixed $id
	 * @return ClassifiedTaxCategory
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 * @return ClassifiedTaxCategory
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPercent()
	{
		return $this->percent;
	}

	/**
	 * @param mixed $percent
	 * @return ClassifiedTaxCategory
	 */
	public function setPercent($percent)
	{
		$this->percent = $percent;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTaxScheme()
	{
		return $this->taxScheme;
	}

	/**
	 * @param mixed $taxScheme
	 * @return ClassifiedTaxCategory
	 */
	public function setTaxScheme($taxScheme)
	{
		$this->taxScheme = $taxScheme;
		return $this;
	}

	/**
	 * The validate function that is called during xml writing to valid the data of the object.
	 *
	 * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
	 * @return void
	 */
	public function validate()
	{
		if ($this->getId() === null) {
			throw new \InvalidArgumentException('Missing taxcategory id');
		}

		if ($this->getName() === null) {
			throw new \InvalidArgumentException('Missing taxcategory name');
		}

		if ($this->getPercent() === null) {
			throw new \InvalidArgumentException('Missing taxcategory percent');
		}
	}

	/**
	 * The xmlSerialize method is called during xml writing.
	 *
	 * @param Writer $writer
	 * @return void
	 */
	function xmlSerialize(Writer $writer)
	{
		$this->validate();

		$writer->write([
			[
				'name' => Schema::CBC . 'ID',
				'value' => $this->getId(),
				'attributes' => [
					'schemeID' => ClassifiedTaxCategory::UNCL5305,
					'schemeName' => 'Duty or tax or fee category'
				]
			],
			Schema::CBC . 'Name' => $this->name,
			Schema::CBC . 'Percent' => number_format($this->percent, 2, '.', ''),
		]);

		if ($this->taxScheme != null) {
			$writer->write([Schema::CAC . 'TaxScheme' => $this->taxScheme]);
		}
	}
}
