<?php

namespace NumNum\UBL;

use Carbon\Carbon;
use DateTime;

use function Sabre\Xml\Deserializer\keyValue;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class PaymentMeans implements XmlSerializable, XmlDeserializable
{
    public $xmlTagName = 'PaymentMeans';
    private $paymentMeansCode = UNCL4461::INSTRUMENT_NOT_DEFINED;
    private $paymentMeansCodeAttributes = [
        'listID'   => 'UN/ECE 4461',
        'listName' => 'Payment Means',
        'listURI'  => 'http://docs.oasis-open.org/ubl/os-UBL-2.0-update/cl/gc/default/PaymentMeansCode-2.0.gc'];
    private $paymentDueDate;
    private $instructionId;
    private $instructionNote;
    private $paymentId;
    private $payeeFinancialAccount;
    private $paymentMandate;

    /**
     * @return string
     */
    public function getPaymentMeansCode(): ?string
    {
        return $this->paymentMeansCode;
    }

    /**
     * @param string $paymentMeansCode
     * @return static
     */
    public function setPaymentMeansCode(?string $paymentMeansCode, $attributes = null)
    {
        $this->paymentMeansCode = $paymentMeansCode;
        if (isset($attributes)) {
            $this->paymentMeansCodeAttributes = $attributes;
        }
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getPaymentDueDate(): ?DateTime
    {
        return $this->paymentDueDate;
    }

    /**
     * @param DateTime $paymentDueDate
     * @return static
     */
    public function setPaymentDueDate(?DateTime $paymentDueDate)
    {
        $this->paymentDueDate = $paymentDueDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstructionId(): ?string
    {
        return $this->instructionId;
    }

    /**
     * @param string $instructionId
     * @return static
     */
    public function setInstructionId(?string $instructionId)
    {
        $this->instructionId = $instructionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstructionNote(): ?string
    {
        return $this->instructionNote;
    }

    /**
     * @param string $instructionNote
     * @return static
     */
    public function setInstructionNote(?string $instructionNote)
    {
        $this->instructionNote = $instructionNote;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    /**
     * @param string $paymentId
     * @return static
     */
    public function setPaymentId(?string $paymentId)
    {
        $this->paymentId = $paymentId;
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
     * @return static
     */
    public function setPayeeFinancialAccount(?PayeeFinancialAccount $payeeFinancialAccount)
    {
        $this->payeeFinancialAccount = $payeeFinancialAccount;
        return $this;
    }

    /**
     * @return PaymentMandate
     */
    public function getPaymentMandate(): ?PaymentMandate
    {
        return $this->paymentMandate;
    }

    /**
     * @param PaymentMandate $paymentMandate
     * @return static
     */
    public function setPaymentMandate(?PaymentMandate $paymentMandate)
    {
        $this->paymentMandate = $paymentMandate;
        return $this;
    }

    public function xmlSerialize(Writer $writer): void
    {
        $writer->write([
            'name'       => Schema::CBC . 'PaymentMeansCode',
            'value'      => $this->paymentMeansCode,
            'attributes' => $this->paymentMeansCodeAttributes
        ]);

        if ($this->getPaymentDueDate() !== null) {
            $writer->write([
                Schema::CBC . 'PaymentDueDate' => $this->getPaymentDueDate()->format('Y-m-d')
            ]);
        }

        if ($this->getInstructionId() !== null) {
            $writer->write([
                Schema::CBC . 'InstructionID' => $this->getInstructionId()
            ]);
        }

        if ($this->getInstructionNote() !== null) {
            $writer->write([
                Schema::CBC . 'InstructionNote' => $this->getInstructionNote()
            ]);
        }

        if ($this->getPaymentId() !== null) {
            $writer->write([
                Schema::CBC . 'PaymentID' => $this->getPaymentId()
            ]);
        }

        if ($this->getPayeeFinancialAccount() !== null) {
            $writer->write([
                Schema::CAC . 'PayeeFinancialAccount' => $this->getPayeeFinancialAccount()
            ]);
        }

        if ($this->getPaymentMandate() !== null) {
            $writer->write([
                Schema::CAC . 'PaymentMandate' => $this->getPaymentMandate()
            ]);
        }
    }

    /**
     * The xmlDeserialize method is called during xml reading.
     * @param Reader $xml
     * @return static
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValues = keyValue($reader);

        return (new static())
            ->setPaymentMeansCode($keyValues[Schema::CBC . 'PaymentMeansCode'] ?? null)
            ->setPaymentDueDate(isset($keyValues[Schema::CBC . 'PaymentDueDate']) ? Carbon::parse($keyValues[Schema::CBC . 'PaymentDueDate'])->toDateTime() : null)
            ->setInstructionId($keyValues[Schema::CBC . 'InstructionID'] ?? null)
            ->setInstructionNote($keyValues[Schema::CBC . 'InstructionNote'] ?? null)
            ->setPaymentId($keyValues[Schema::CBC . 'PaymentID'] ?? null)
            ->setPayeeFinancialAccount($keyValues[Schema::CAC . 'PayeeFinancialAccount'] ?? null)
            ->setPaymentMandate($keyValues[Schema::CAC . 'PaymentMandate'] ?? null);
    }
}
