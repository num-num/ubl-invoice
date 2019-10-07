<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Invoice implements XmlSerializable
{
	private $UBLVersionID = '2.1';
	private $CustomizationID = '1.0';
	private $id;
	private $copyIndicator = false;
	private $issueDate;
	private $invoiceTypeCode;
	private $taxPointDate;
	private $paymentTerms;
	private $accountingSupplierParty;
	private $accountingCustomerParty;
	private $paymentMeans;
	private $taxTotal;
	private $legalMonetaryTotal;
	private $invoiceLines;
	private $allowanceCharges;
	private $additionalDocumentReference;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 * @return Invoice
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isCopyIndicator()
	{
		return $this->copyIndicator;
	}

	/**
	 * @param bool $copyIndicator
	 * @return Invoice
	 */
	public function setCopyIndicator(bool $copyIndicator)
	{
		$this->copyIndicator = $copyIndicator;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getIssueDate()
	{
		return $this->issueDate;
	}

	/**
	 * @param \DateTime $issueDate
	 * @return Invoice
	 */
	public function setIssueDate(\DateTime $issueDate)
	{
		$this->issueDate = $issueDate;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getInvoiceTypeCode()
	{
		return $this->invoiceTypeCode;
	}

	/**
	 * @param string $invoiceTypeCode
	 * @return Invoice
	 */
	public function setInvoiceTypeCode(string $invoiceTypeCode)
	{
		$this->invoiceTypeCode = $invoiceTypeCode;
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getTaxPointDate()
	{
		return $this->taxPointDate;
	}

	/**
	 * @param DateTime $taxPointDate
	 * @return Invoice
	 */
	public function setTaxPointDate(\DateTime $taxPointDate)
	{
		$this->taxPointDate = $taxPointDate;
		return $this;
	}

	/**
	 * @return PaymentTerms
	 */
	public function getPaymentTerms()
	{
		return $this->paymentTerms;
	}

	/**
	 * @param PaymentTerms $paymentTerms
	 * @return Invoice
	 */
	public function setPaymentTerms(PaymentTerms $paymentTerms)
	{
		$this->paymentTerms = $paymentTerms;
		return $this;
	}

	/**
	 * @return Party
	 */
	public function getAccountingSupplierParty()
	{
		return $this->accountingSupplierParty;
	}

	/**
	 * @param Party $accountingSupplierParty
	 * @return Invoice
	 */
	public function setAccountingSupplierParty(Party $accountingSupplierParty)
	{
		$this->accountingSupplierParty = $accountingSupplierParty;
		return $this;
	}

	/**
	 * @return Party
	 */
	public function getAccountingCustomerParty()
	{
		return $this->accountingCustomerParty;
	}

	/**
	 * @param Party $accountingCustomerParty
	 * @return Invoice
	 */
	public function setAccountingCustomerParty(Party $accountingCustomerParty)
	{
		$this->accountingCustomerParty = $accountingCustomerParty;
		return $this;
	}

	/**
	 * @return PaymentMeans
	 */
	public function getPaymentMeans()
	{
		return $this->paymentMeans;
	}

	/**
	 * @param PaymentMeans $paymentMeans
	 * @return Invoice
	 */
	public function setPaymentMeans(PaymentMeans $paymentMeans)
	{
		$this->paymentMeans = $paymentMeans;
		return $this;
	}

	/**
	 * @return TaxTotal
	 */
	public function getTaxTotal()
	{
		return $this->taxTotal;
	}

	/**
	 * @param TaxTotal $taxTotal
	 * @return Invoice
	 */
	public function setTaxTotal(TaxTotal $taxTotal)
	{
		$this->taxTotal = $taxTotal;
		return $this;
	}

	/**
	 * @return LegalMonetaryTotal
	 */
	public function getLegalMonetaryTotal()
	{
		return $this->legalMonetaryTotal;
	}

	/**
	 * @param LegalMonetaryTotal $legalMonetaryTotal
	 * @return Invoice
	 */
	public function setLegalMonetaryTotal(LegalMonetaryTotal $legalMonetaryTotal)
	{
		$this->legalMonetaryTotal = $legalMonetaryTotal;
		return $this;
	}

	/**
	 * @return InvoiceLine[]
	 */
	public function getInvoiceLines()
	{
		return $this->invoiceLines;
	}

	/**
	 * @param InvoiceLine[] $invoiceLines
	 * @return Invoice
	 */
	public function setInvoiceLines(Array $invoiceLines)
	{
		$this->invoiceLines = $invoiceLines;
		return $this;
	}

	/**
	 * @return AllowanceCharge[]
	 */
	public function getAllowanceCharges()
	{
		return $this->allowanceCharges;
	}

	/**
	 * @param AllowanceCharge[] $allowanceCharges
	 * @return Invoice
	 */
	public function setAllowanceCharges(Array $allowanceCharges)
	{
		$this->allowanceCharges = $allowanceCharges;
		return $this;
	}

	/**
	 * @return AdditionalDocumentReference
	 */
	public function getAdditionalDocumentReference()
	{
		return $this->additionalDocumentReference;
	}

	/**
	 * @param AdditionalDocumentReference $additionalDocumentReference
	 * @return Invoice
	 */
	public function setAdditionalDocumentReference(AdditionalDocumentReference $additionalDocumentReference)
	{
		$this->additionalDocumentReference = $additionalDocumentReference;
		return $this;
	}

	/**
	 * The validate function that is called during xml writing to valid the data of the object.
	 *
	 * @throws InvalidArgumentException An error with information about required data that is missing to write the XML
	 * @return void
	 */
	function validate()
	{
		if ($this->id === null) {
			throw new \InvalidArgumentException('Missing invoice id');
		}

		if ($this->id === null) {
			throw new \InvalidArgumentException('Missing invoice id');
		}

		if (!$this->issueDate instanceof \DateTime) {
			throw new \InvalidArgumentException('Invalid invoice issueDate');
		}

		if ($this->invoiceTypeCode === null) {
			throw new \InvalidArgumentException('Missing invoice invoiceTypeCode');
		}

		if ($this->accountingSupplierParty === null) {
			throw new \InvalidArgumentException('Missing invoice accountingSupplierParty');
		}

		if ($this->accountingCustomerParty === null) {
			throw new \InvalidArgumentException('Missing invoice accountingCustomerParty');
		}

		if ($this->invoiceLines === null) {
			throw new \InvalidArgumentException('Missing invoice lines');
		}

		if ($this->legalMonetaryTotal === null) {
			throw new \InvalidArgumentException('Missing invoice LegalMonetaryTotal');
		}
	}

	/**
	 * The xmlSerialize method is called during xml writing.
	 * @param Writer $writer
	 * @return void
	 */
	function xmlSerialize(Writer $writer)
	{
		$this->validate();

		$writer->write([
			Schema::CBC . 'UBLVersionID' => $this->UBLVersionID,
			Schema::CBC . 'CustomizationID' => $this->UBLVersionID,
			Schema::CBC . 'ID' => $this->id,
			Schema::CBC . 'CopyIndicator' => $this->copyIndicator ? 'true' : 'false',
			Schema::CBC . 'IssueDate' => $this->issueDate->format('Y-m-d'),
			[
				'name' => Schema::CBC . 'InvoiceTypeCode',
				'value' => $this->invoiceTypeCode,
				'attributes' => [
					'listURI' => 'http://www.E-FFF.be/ubl/2.0/cl/gc/BE-InvoiceCode-1.0.gc'
				]
			]
		]);

		if ($this->taxPointDate != null) {
			$writer->write([
				Schema::CBC . 'TaxPointDate' => $this->taxPointDate->format('Y-m-d')
			]);
		}

		$writer->write([
			Schema::CBC . 'DocumentCurrencyCode' => 'EUR',
		]);

		if ($this->additionalDocumentReference != null) {
			$writer->write([
				Schema::CAC . 'AdditionalDocumentReference' => $this->additionalDocumentReference
			]);
		}

		$writer->write([
			Schema::CAC . 'AccountingSupplierParty' => [Schema::CAC . "Party" => $this->accountingSupplierParty],
			Schema::CAC . 'AccountingCustomerParty' => [Schema::CAC . "Party" => $this->accountingCustomerParty],
		]);

		if ($this->paymentMeans != null) {
			$writer->write([
				Schema::CAC . 'PaymentMeans' => $this->paymentMeans
			]);
		}

		if ($this->paymentTerms != null) {
			$writer->write([
				Schema::CAC . 'PaymentTerms' => $this->paymentTerms
			]);
		}

		if ($this->allowanceCharges != null) {
			foreach ($this->allowanceCharges as $invoiceLine) {
				$writer->write([
					Schema::CAC . 'AllowanceCharge' => $invoiceLine
				]);
			}
		}

		if ($this->taxTotal != null) {
			$writer->write([
				Schema::CAC . 'TaxTotal' => $this->taxTotal
			]);
		}

		$writer->write([
			Schema::CAC . 'LegalMonetaryTotal' => $this->legalMonetaryTotal
		]);

		foreach ($this->invoiceLines as $invoiceLine) {
			$writer->write([
				Schema::CAC . 'InvoiceLine' => $invoiceLine
			]);
		}
	}
}
