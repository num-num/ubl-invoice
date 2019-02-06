<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class PaymentTerms implements XmlSerializable
{
	private $note;
	private $settlementDiscountPercent;
	private $amount;
	private $settlementPeriod;

	/**
	 * @return mixed
	 */
	public function getNote()
	{
		return $this->note;
	}

	/**
	 * @param mixed $note
	 * @return String
	 */
	public function setNote($note)
	{
		$this->note = $note;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSettlementDiscountPercent()
	{
		return $this->settlementDiscountPercent;
	}

	/**
	 * @param mixed $settlementDiscountPercent
	 * @return float
	 */
	public function setSettlementDiscountPercent($settlementDiscountPercent)
	{
		$this->settlementDiscountPercent = $settlementDiscountPercent;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * @param mixed $amount
	 * @return float
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSettlementPeriod()
	{
		return $this->settlementPeriod;
	}

	/**
	 * @param mixed $settlementPeriod
	 * @return SettlementPeriod  <?=$t->g('translation_key');?>
	 */
	public function setSettlementPeriod(SettlementPeriod $settlementPeriod)
	{
		$this->settlementPeriod = $settlementPeriod;
		return $this;
	}

	function xmlSerialize(Writer $writer)
	{
		if ($this->note !== null) {
			$writer->write([ Schema::CBC . 'Note' => $this->note ]);
		}

		if ($this->settlementDiscountPercent != null) {
			$writer->write([ Schema::CBC . 'SettlementDiscountPercent' => $this->settlementDiscountPercent ]);
		}

		if ($this->amount != null) {
			$writer->write([
				[
					'name' => Schema::CBC . 'Amount',
					'value' => number_format($this->amount, 2, '.', ''),
					'attributes' => [
						'currencyID' => 'EUR'
					]
				]
			]);
		}

		if ($this->settlementPeriod != null) {
			$writer->write([ Schema::CAC . 'SettlementPeriod' => $this->settlementPeriod ]);
		}
	}
}
