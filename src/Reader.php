<?php

namespace NumNum\UBL;

use Sabre\Xml\Service;

class Reader
{
    public static $currencyID;

    public static function invoice($currencyId = 'EUR'): Service
    {
        self::$currencyID = $currencyId;

        $xmlService = new Service();

        $xmlService->namespaceMap = [
            Schema::INVOICE => '',
            Schema::CBC     => 'cbc',
            Schema::CAC     => 'cac',
        ];

        $xmlService->elementMap = [
            Schema::INVOICE.'Invoice'          => fn ($reader) => Invoice::xmlDeserialize($reader),
            Schema::CAC.    'PaymentTerms'     => fn ($reader) => PaymentTerms::xmlDeserialize($reader),
            Schema::CAC.    'SettlementPeriod' => fn ($reader) => SettlementPeriod::xmlDeserialize($reader),
        ];

        return $xmlService;
    }
}
