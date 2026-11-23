<?php

namespace NumNum\UBL;

use Sabre\Xml\Service;

class Generator
{
    public static string $currencyID = 'EUR';

    /**
     * @param Invoice $invoice
     * @param string $currencyId
     * @param array<string, string> $additionalNamespaces Extra namespaces for the root element, as [uri => prefix]
     * @return string
     */
    public static function invoice(Invoice $invoice, string $currencyId = 'EUR', array $additionalNamespaces = [])
    {
        return self::write($invoice, $currencyId, $additionalNamespaces);
    }

    /**
     * @param CreditNote $creditNote
     * @param string $currencyId
     * @param array<string, string> $additionalNamespaces Extra namespaces for the root element, as [uri => prefix]
     * @return string
     */
    public static function creditNote(CreditNote $creditNote, string $currencyId = 'EUR', array $additionalNamespaces = [])
    {
        return self::write($creditNote, $currencyId, $additionalNamespaces);
    }

    /**
     * @param DebitNote $debitNote
     * @param string $currencyId
     * @param array<string, string> $additionalNamespaces Extra namespaces for the root element, as [uri => prefix]
     * @return string
     */
    public static function debitNote(DebitNote $debitNote, string $currencyId = 'EUR', array $additionalNamespaces = [])
    {
        return self::write($debitNote, $currencyId, $additionalNamespaces);
    }

    private static function write(Invoice $document, string $currencyId, array $additionalNamespaces): string
    {
        self::$currencyID = $currencyId;

        $xmlService = new Service();

        $xmlService->namespaceMap = [
            'urn:oasis:names:specification:ubl:schema:xsd:' . $document->xmlTagName . '-2' => '',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2'         => 'cbc',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2'     => 'cac'
        ];

        if ($document->getExtensions()) {
            $xmlService->namespaceMap['urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2'] = 'ext';
        }

        foreach ($additionalNamespaces as $namespace => $prefix) {
            $xmlService->namespaceMap[$namespace] = $prefix;
        }

        return $xmlService->write($document->xmlTagName, [
            $document
        ]);
    }
}
