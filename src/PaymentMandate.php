<?php

namespace NumNum\UBL;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

use function Sabre\Xml\Deserializer\keyValue;

class PaymentMandate implements XmlSerializable, XmlDeserializable
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
     * @return static
     */
    public function setId(?string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return PayeeFinancialAccount
     */
    public function getPayeeFinancialAccount(): ?PayeeFinancialAccount
    {
        return $this->payeeFinancialAccount;
    }

    /**
     * @param PayeeFinancialAccount $payeeFinancialAccount
     * @return static
     */
    public function setPayeeFinancialAccount(?PayeeFinancialAccount $payeeFinancialAccount)
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

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $reader
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        return (new static())
            ->setId($keyValues[Schema::CBC . 'ID'] ?? null)
            ->setPayeeFinancialAccount($keyValues[Schema::CAC . 'PayeeFinancialAccount'] ?? null);
    }
}
