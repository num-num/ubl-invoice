<?php

namespace NumNum\UBL;

use Sabre\Xml\Service;

class Reader
{
    public static $currencyID;

    public static function ubl($currencyId = 'EUR'): Service
    {
        self::$currencyID = $currencyId;

        $xmlService = new Service();

        $xmlService->namespaceMap = [
            Schema::INVOICE => '',
            Schema::CBC     => 'cbc',
            Schema::CAC     => 'cac',
        ];

        $xmlService->elementMap = [
            Schema::INVOICE.   'Invoice'                      => fn ($reader) => Invoice::xmlDeserialize($reader),
            Schema::CREDITNOTE.'CreditNote'                   => fn ($reader) => CreditNote::xmlDeserialize($reader),
            Schema::CAC.        'AccountingCustomerParty'     => fn ($reader) => AccountingParty::xmlDeserialize($reader),
            Schema::CAC.        'AccountingSupplierParty'     => fn ($reader) => AccountingParty::xmlDeserialize($reader),
            Schema::CAC.        'AdditionalDocumentReference' => fn ($reader) => AdditionalDocumentReference::xmlDeserialize($reader),
            Schema::CAC.        'Address'                     => fn ($reader) => Address::xmlDeserialize($reader),
            Schema::CAC.        'AllowanceCharge'             => fn ($reader) => AllowanceCharge::xmlDeserialize($reader),
            Schema::CAC.        'Attachment'                  => fn ($reader) => Attachment::xmlDeserialize($reader),
            Schema::CAC.        'BillingReference'            => fn ($reader) => BillingReference::xmlDeserialize($reader),
            Schema::CAC.        'ClassifiedTaxCategory'       => fn ($reader) => ClassifiedTaxCategory::xmlDeserialize($reader),
            Schema::CAC.        'CommodityClassification'     => fn ($reader) => CommodityClassification::xmlDeserialize($reader),
            Schema::CAC.        'Contact'                     => fn ($reader) => Contact::xmlDeserialize($reader),
            Schema::CAC.        'ContractDocumentReference'   => fn ($reader) => ContractDocumentReference::xmlDeserialize($reader),
            Schema::CAC.        'Country'                     => fn ($reader) => Country::xmlDeserialize($reader),
            Schema::CAC.        'CreditNoteLine'              => fn ($reader) => CreditNoteLine::xmlDeserialize($reader),
            Schema::CAC.        'CreditNoteLine'              => fn ($reader) => CreditNoteLine::xmlDeserialize($reader),
            Schema::CAC.        'Delivery'                    => fn ($reader) => Delivery::xmlDeserialize($reader),
            Schema::CAC.        'FinancialInstitutionBranch'  => fn ($reader) => FinancialInstitutionBranch::xmlDeserialize($reader),
            Schema::CAC.        'InvoiceDocumentReference'    => fn ($reader) => InvoiceDocumentReference::xmlDeserialize($reader),
            Schema::CAC.        'InvoiceLine'                 => fn ($reader) => InvoiceLine::xmlDeserialize($reader),
            Schema::CAC.        'InvoicePeriod'               => fn ($reader) => InvoicePeriod::xmlDeserialize($reader),
            Schema::CAC.        'Item'                        => fn ($reader) => Item::xmlDeserialize($reader),
            Schema::CAC.        'LegalMonetaryTotal'          => fn ($reader) => LegalMonetaryTotal::xmlDeserialize($reader),
            Schema::CAC.        'OrderLineReference'          => fn ($reader) => OrderLineReference::xmlDeserialize($reader),
            Schema::CAC.        'OrderReference'              => fn ($reader) => OrderReference::xmlDeserialize($reader),
            Schema::CAC.        'Party'                       => fn ($reader) => Party::xmlDeserialize($reader),
            Schema::CAC.        'PartyLegalEntity'            => fn ($reader) => LegalEntity::xmlDeserialize($reader),
            Schema::CAC.        'PartyTaxScheme'              => fn ($reader) => PartyTaxScheme::xmlDeserialize($reader),
            Schema::CAC.        'PayeeFinancialAccount'       => fn ($reader) => PayeeFinancialAccount::xmlDeserialize($reader),
            Schema::CAC.        'PayeeParty'                  => fn ($reader) => Party::xmlDeserialize($reader),
            Schema::CAC.        'PaymentMeans'                => fn ($reader) => PaymentMeans::xmlDeserialize($reader),
            Schema::CAC.        'PaymentTerms'                => fn ($reader) => PaymentTerms::xmlDeserialize($reader),
            Schema::CAC.        'PostalAddress'               => fn ($reader) => Address::xmlDeserialize($reader),
            Schema::CAC.        'Price'                       => fn ($reader) => Price::xmlDeserialize($reader),
            Schema::CAC.        'ProjectReference'            => fn ($reader) => ProjectReference::xmlDeserialize($reader),
            Schema::CAC.        'SettlementPeriod'            => fn ($reader) => SettlementPeriod::xmlDeserialize($reader),
            Schema::CAC.        'TaxCategory'                 => fn ($reader) => TaxCategory::xmlDeserialize($reader),
            Schema::CAC.        'TaxScheme'                   => fn ($reader) => TaxScheme::xmlDeserialize($reader),
            Schema::CAC.        'TaxSubtotal'                 => fn ($reader) => TaxSubTotal::xmlDeserialize($reader),
            Schema::CAC.        'TaxTotal'                    => fn ($reader) => TaxTotal::xmlDeserialize($reader),
        ];

        return $xmlService;
    }
}
