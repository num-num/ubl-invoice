<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;
use DateTime;

class PaymentMandate implements XmlSerializable
{
    public $xmlTagName = 'PaymentMandate';

    private $id;
    private $payeeFinancialAccount;
    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return PaymentMandate
     */
    public function setId(?string $id): PaymentMandate
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getPayeeFinancialAccount(): ?PayeeFinancialAccount
    {
        return $this->payeeFinancialAccount;
    }

    /**
     * @param mixed $payeeFinancialAccount
     * @return PaymentMeans
     */
    public function setPayeeFinancialAccount(?PayeeFinancialAccount $payeeFinancialAccount): PaymentMeans
    {
        $this->payeeFinancialAccount = $payeeFinancialAccount;
        return $this;
    }


    public function xmlSerialize(Writer $writer): void
    {
        $writer->write([
            Schema::CBC . 'ID' => $this->id
        ]);

        if ($this->getPayeeFinancialAccount() !== null) {
            $writer->write([
                Schema::CAC . 'PayeeFinancialAccount' => $this->getPayeeFinancialAccount()
            ]);
        }
    }
}
