<?php
/**
 * Created by PhpStorm.
 * User: bram.vaneijk
 * Date: 25-10-2016
 * Time: 14:17
 */

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class TaxTotal implements XmlSerializable {
    private $taxAmount;
    /**
     * @var TaxSubTotal
     */
    private $taxSubTotal;

    /**
     * @return mixed
     */
    public function getTaxAmount() {
        return $this->taxAmount;
    }

    /**
     * @param mixed $taxAmount
     * @return TaxTotal
     */
    public function setTaxAmount($taxAmount) {
        $this->taxAmount = $taxAmount;
        return $this;
    }

    /**
     * @return TaxSubTotal
     */
    public function getTaxSubTotal() {
        return $this->taxSubTotal;
    }

    /**
     * @param TaxSubTotal $taxSubTotal
     * @return TaxTotal
     */
    public function setTaxSubTotal($taxSubTotal) {
        $this->taxSubTotal = $taxSubTotal;
        return $this;
    }

    public function validate(){
        if($this->taxAmount === null){
            throw new \InvalidArgumentException('Missing taxtotal taxamount');
        }
        if($this->taxSubTotal === null){
            throw new \InvalidArgumentException('Missing taxtotal taxsubtotal');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     * @param Writer $writer
     * @return void
     */
    function xmlSerialize(Writer $writer) {
        $this->validate();

        $writer->write([
            [
                'name' => Schema::CBC . 'TaxAmount',
                'value' => $this->taxAmount,
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ],
            Schema::CAC . 'TaxSubtotal' => $this->taxSubTotal
        ]);
    }
}