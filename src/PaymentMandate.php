<?php

namespace NumNum\UBL;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

use function Sabre\Xml\Deserializer\keyValue;

class PaymentMandate implements XmlSerializable, XmlDeserializable
{
    private $id;
    private $payerFinancialAccount;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
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
                Schema::CAC . $this->payerFinancialAccount->xmlTagName => $this->getPayerFinancialAccount(),
            ]);
        }
    }

    public function getPayerFinancialAccount(): ?PayerFinancialAccount
    {
        return $this->payerFinancialAccount;
    }

    public function setPayerFinancialAccount(?PayerFinancialAccount $payerFinancialAccount): self
    {
        $this->payerFinancialAccount = $payerFinancialAccount;
        return $this;
    }

    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        return (new static())
            ->setId($keyValues[Schema::CBC . 'ID'] ?? null)
            ->setPayerFinancialAccount($keyValues[Schema::CAC . 'PayerFinancialAccount'] ?? null);
    }
}
