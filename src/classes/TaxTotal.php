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
     * @var array
     */
    private $taxSubTotals = array();

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
     * @return array
     */
    public function getTaxSubTotals() {
        return $this->taxSubTotals;
    }

    /**
     * @param TaxSubTotal $taxSubTotal
     * @return TaxTotal
     */
    public function addTaxSubTotal($taxSubTotal) {
        $this->taxSubTotals[] = $taxSubTotal;
        return $this;
    }

    public function validate(){
        if($this->taxAmount === null){
            throw new \InvalidArgumentException('Missing taxtotal taxamount');
        }
        if(count($this->taxSubTotals) === 0){
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
                'value' => number_format($this->taxAmount, 2, '.', ''),
                'attributes' => [
                    'currencyID' => Generator::$currencyID
                ]
            ],
        ]);

        foreach($this->taxSubTotals as $taxSubTotal){
            $writer->write([Schema::CAC . 'TaxSubtotal' => $taxSubTotal]);
        }
    }
}
