<?php

namespace NumNum\UBL\Tests\Write;

use NumNum\UBL\AccountingParty;
use NumNum\UBL\Address;
use NumNum\UBL\Country;
use NumNum\UBL\DebitNote;
use NumNum\UBL\DebitNoteLine;
use NumNum\UBL\Extension;
use NumNum\UBL\Generator;
use NumNum\UBL\Item;
use NumNum\UBL\LegalMonetaryTotal;
use NumNum\UBL\Party;
use NumNum\UBL\Price;
use NumNum\UBL\Reader;
use NumNum\UBL\TaxCategory;
use NumNum\UBL\TaxSubTotal;
use NumNum\UBL\TaxTotal;
use NumNum\UBL\UnitCode;
use PHPUnit\Framework\TestCase;

/**
 * Test that credit and debit notes write UBLExtensions with the ext prefix
 * and accept additional namespaces, like invoices do
 */
class CreditDebitNoteExtensionTest extends TestCase
{
    private const CUSTOM_NAMESPACE = 'urn:example:custom-extension';

    private function extension(): Extension
    {
        return (new Extension())
            ->setContent(['{' . self::CUSTOM_NAMESPACE . '}Data' => 'value']);
    }

    public function testCreditNoteWithExtension()
    {
        $creditNote = Reader::ubl()->parse(file_get_contents(__DIR__ . '/../Files/UBL-CreditNote.xml'));
        $creditNote->addExtension($this->extension());

        $xml = Generator::creditNote($creditNote, 'EUR', [self::CUSTOM_NAMESPACE => 'custom']);

        $this->assertStringContainsString('<ext:UBLExtensions>', $xml);
        $this->assertStringContainsString('<custom:Data>value</custom:Data>', $xml);
        $this->assertStringNotContainsString('x1:', $xml);
    }

    public function testDebitNoteWithExtension()
    {
        $debitNote = $this->debitNote();
        $debitNote->addExtension($this->extension());

        $xml = Generator::debitNote($debitNote, 'EUR', [self::CUSTOM_NAMESPACE => 'custom']);

        $this->assertStringContainsString('<ext:UBLExtensions>', $xml);
        $this->assertStringContainsString('<custom:Data>value</custom:Data>', $xml);
        $this->assertStringNotContainsString('x1:', $xml);
    }

    private function debitNote(): DebitNote
    {
        $country = (new Country())->setIdentificationCode('BE');
        $party = (new Party())
            ->setName('Company')
            ->setPostalAddress((new Address())->setCityName('City')->setCountry($country));

        $taxCategory = (new TaxCategory())->setId('S')->setPercent(21);

        $line = (new DebitNoteLine())
            ->setId(1)
            ->setItem((new Item())->setName('Product'))
            ->setPrice((new Price())->setBaseQuantity(1)->setUnitCode(UnitCode::UNIT)->setPriceAmount(10))
            ->setLineExtensionAmount(10);

        return (new DebitNote())
            ->setId('DN-1')
            ->setIssueDate(new \DateTime('2026-11-01'))
            ->setAccountingSupplierParty((new AccountingParty())->setParty($party))
            ->setAccountingCustomerParty((new AccountingParty())->setParty($party))
            ->setDebitNoteLines([$line])
            ->setLegalMonetaryTotal((new LegalMonetaryTotal())->setPayableAmount(12.1))
            ->setTaxTotal((new TaxTotal())->setTaxAmount(2.1)->addTaxSubTotal(
                (new TaxSubTotal())->setTaxableAmount(10)->setTaxAmount(2.1)->setTaxCategory($taxCategory)
            ));
    }
}
