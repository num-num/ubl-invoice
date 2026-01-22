<?php

namespace NumNum\UBL;

use Sabre\Xml\Service;

class Generator
{
    public static $currencyID;

    public static function invoice(Invoice $invoice, $currencyId = 'EUR')
    {
        self::$currencyID = $currencyId;

        $xmlService = new Service();

        $xmlService->namespaceMap = [
            'urn:oasis:names:specification:ubl:schema:xsd:' . $invoice->xmlTagName . '-2' => '',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2'        => 'cbc',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2'    => 'cac'
        ];

        return $xmlService->write($invoice->xmlTagName, [
            $invoice
        ]);
    }

    public static function creditNote(CreditNote $creditNote, $currencyId = 'EUR')
    {
        self::$currencyID = $currencyId;

        $xmlService = new Service();

        $xmlService->namespaceMap = [
            'urn:oasis:names:specification:ubl:schema:xsd:' . $creditNote->xmlTagName . '-2' => '',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2'           => 'cbc',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2'       => 'cac'
        ];

        return $xmlService->write($creditNote->xmlTagName, [
            $creditNote
        ]);
    }

    public static function debitNote(DebitNote $debitNote, $currencyId = 'EUR')
    {
        self::$currencyID = $currencyId;

        $xmlService = new Service();

        $xmlService->namespaceMap = [
            'urn:oasis:names:specification:ubl:schema:xsd:' . $debitNote->xmlTagName . '-2' => '',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2'          => 'cbc',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2'      => 'cac'
        ];

        return $xmlService->write($debitNote->xmlTagName, [
            $debitNote
        ]);
    }
}
