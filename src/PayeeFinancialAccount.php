<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class PayeeFinancialAccount implements XmlSerializable
{
    private $id;
    private $name;
    private $financialInstitutionBranch;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return PayeeFinancialAccount
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
     * @return PayeeFinancialAccount
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFinancialInstitutionBranch()
    {
        return $this->financialInstitutionBranch;
    }

    /**
     * @param mixed $financialInstitutionBranch
     * @return PayeeFinancialAccount
     */
    public function setFinancialInstitutionBranch($financialInstitutionBranch)
    {
        $this->financialInstitutionBranch = $financialInstitutionBranch;
        return $this;
    }

    public function xmlSerialize(Writer $writer)
    {
        $writer->write([
            'name' => Schema::CBC . 'ID',
            'value' => $this->id,
            'attributes' => [
                //'schemeID' => 'IBAN'
            ]
        ]);

        if ($this->getName() !== null) {
            $writer->write([
                Schema::CBC . 'Name' => $this->getName()
            ]);
        }

        if ($this->getFinancialInstitutionBranch() !== null) {
            $writer->write([
                Schema::CAC . 'FinancialInstitutionBranch' => $this->getFinancialInstitutionBranch()
            ]);
        }
    }
}
