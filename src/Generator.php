<?php

namespace NumNum\UBL;

use Sabre\Xml\Service;

class Generator
{
    public static $currencyID;

    public static function invoice(Invoice $invoice, $currencyId = 'EUR', array $additionalNamespaces  = [])
    {
        self::$currencyID = $currencyId;

        $xmlService = new Service();

        $xmlService->namespaceMap = [
            'urn:oasis:names:specification:ubl:schema:xsd:' . $invoice->xmlTagName . '-2' => '',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2'        => 'cbc',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2'    => 'cac'
        ];

        if ($invoice->getExtensions()) {
            $xmlService->namespaceMap['urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2'] = 'ext';
        }

        foreach ($additionalNamespaces as $namespace => $prefix) {
            $xmlService->namespaceMap[$namespace] = $prefix;
        }

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
