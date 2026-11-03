<?php

namespace NumNum\UBL;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

use function Sabre\Xml\Deserializer\keyValue;

class PaymentMandate implements XmlSerializable, XmlDeserializable
{
    public string $xmlTagName = 'PaymentMandate';

    private ?string $id = null;
    private ?PayerFinancialAccount $payerFinancialAccount = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return static
     */
    public function setId(?string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return PayerFinancialAccount|null
     */
    public function getPayerFinancialAccount(): ?PayerFinancialAccount
    {
        return $this->payerFinancialAccount;
    }

    /**
     * @param PayerFinancialAccount|null $payerFinancialAccount
     * @return static
     */
    public function setPayerFinancialAccount(?PayerFinancialAccount $payerFinancialAccount)
    {
        $this->payerFinancialAccount = $payerFinancialAccount;
        return $this;
    }

    public function xmlSerialize(Writer $writer): void
    {
        if ($this->id !== null) {
            $writer->write([
                Schema::CBC . 'ID' => $this->id,
            ]);
        }

        if ($this->getPayerFinancialAccount() !== null) {
            $writer->write([
                Schema::CAC . 'PayerFinancialAccount' => $this->getPayerFinancialAccount(),
            ]);
        }
    }

    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        return (new static())
            ->setId($keyValues[Schema::CBC . 'ID'] ?? null)
            ->setPayerFinancialAccount($keyValues[Schema::CAC . 'PayerFinancialAccount'] ?? null);
    }
}
