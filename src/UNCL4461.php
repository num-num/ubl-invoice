<?php

namespace NumNum\UBL;

/**
 * All possible Payment Means Codes that can be used
 * To extend, see also: https://docs.peppol.eu/poacc/billing/3.0/codelist/UNCL4461/
 */
class UNCL4461
{
    public const INSTRUMENT_NOT_DEFINED = "1";
    public const AUTOMATED_CLEARING_HOUSE_CREDIT = "2";
    public const AUTOMATED_CLEARING_HOUSE_DEBIT = "3";
    public const ACH_DEMAND_DEBIT_REVERSAL = "4";
    public const ACH_DEMAND_CREDIT_REVERSAL = "5";
    public const ACH_DEMAND_CREDIT = "6";
    public const ACH_DEMAND_DEBIT = "7";
    public const HOLD = "8";
    public const NATIONAL_OR_REGIONAL_CLEARING = "9";
    public const IN_CASH = "10";
    public const ACH_SAVINGS_CREDIT_REVERSAL = "11";
    public const ACH_SAVINGS_DEBIT_REVERSAL = "12";
    public const ACH_SAVINGS_CREDIT = "13";
    public const ACH_SAVINGS_DEBIT = "14";
    public const BOOKENTRY_CREDIT = "15";
    public const BOOKENTRY_DEBIT = "16";
    public const ACH_DEMAND_CASH_CONCENTRATION_OR_DISBURSEMENT_CREDIT = "17";
    public const ACH_DEMAND_CASH_CONCENTRATION_OR_DISBURSEMENT_DEBIT = "18";
    public const ACH_DEMAND_CORPORATE_TRADE_PAYMENT_CREDIT = "19";
    public const CHEQUE = "20";
    public const BANKERS_DRAFT = "21";
    public const CERTIFIED_BANKERS_DRAFT = "22";
    public const BANK_CHEQUE_ISSUED_BY_A_BANKING_OR_SIMILAR_ESTABLISHMENT = "23";
    public const BILL_OF_EXCHANGE_AWAITING_ACCEPTANCE = "24";
    public const CERTIFIED_CHEQUE = "25";
    public const LOCAL_CHEQUE = "26";
    public const ACH_DEMAND_CORPORATE_TRADE_PAYMENT_DEBIT = "27";
    public const ACH_DEMAND_CORPORATE_TRADE_EXCHANGE_CREDIT = "28";
    public const ACH_DEMAND_CORPORATE_TRADE_EXCHANGE_DEBIT = "29";
    public const CREDIT_TRANSFER = "30";
    public const DEBIT_TRANSFER = "31";
    public const ACH_DEMAND_CASH_CONCENTRATION_OR_DISBURSEMENT_PLUS_CREDIT = "32";
    public const ACH_DEMAND_CASH_CONCENTRATION_OR_DISBURSEMENT_PLUS_DEBIT = "33";
    public const ACH_PREARRANGED_PAYMENT_AND_DEPOSIT = "34";
    public const ACH_SAVINGS_CASH_CONCENTRATION_OR_DISBURSEMENT_CREDIT = "35";
    public const ACH_SAVINGS_CASH_CONCENTRATION_OR_DISBURSEMENT_DEBIT = "36";
    public const ACH_SAVINGS_CORPORATE_TRADE_PAYMENT_CREDIT = "37";
    public const ACH_SAVINGS_CORPORATE_TRADE_PAYMENT_DEBIT = "38";
    public const ACH_SAVINGS_CORPORATE_TRADE_EXCHANGE_CREDIT = "39";
    public const ACH_SAVINGS_CORPORATE_TRADE_EXCHANGE_DEBIT = "40";
    public const ACH_SAVINGS_CASH_CONCENTRATION_OR_DISBURSEMENT_PLUS_CREDIT = "41";
    public const PAYMENT_TO_BANK_ACCOUNT = "42";
    public const ACH_SAVINGS_CASH_CONCENTRATION_OR_DISBURSEMENT_PLUS_DEBIT = "43";
    public const ACCEPTED_BILL_OF_EXCHANGE = "44";
    public const REFERENCED_HOME_BANKING_CREDIT_TRANSFER = "45";
    public const INTERBANK_DEBIT_TRANSFER = "46";
    public const HOME_BANKING_DEBIT_TRANSFER = "47";
    public const BANK_CARD = "48";
    public const DIRECT_DEBIT = "49";
    public const PAYMENT_BY_POSTGIRO = "50";
    public const FR_NORME_6_97_TELEREGLEMENT_CFONB = "51";
    public const URGENT_COMMERCIAL_PAYMENT = "52";
    public const URGENT_TREASURY_PAYMENT = "53";
    public const CREDIT_CARD = "54";
    public const DEBIT_CARD = "55";
    public const BANKGIRO = "56";
    public const STANDING_AGREEMENT = "57";
    public const SEPA_CREDIT_TRANSFER = "58";
    public const SEPA_DIRECT_DEBIT = "59";
    public const PROMISSORY_NOTE = "60";
    public const PROMISSORY_NOTE_SIGNED_BY_THE_DEBTOR = "61";
    public const PROMISSORY_NOTE_SIGNED_BY_THE_DEBTOR_AND_ENDORSED_BY_A_BANK = "62";
    public const PROMISSORY_NOTE_SIGNED_BY_THE_DEBTOR_AND_ENDORSED_BY_A_THIRD_PARTY = "63";
    public const PROMISSORY_NOTE_SIGNED_BY_A_BANK = "64";
    public const PROMISSORY_NOTE_SIGNED_BY_A_BANK_AND_ENDORSED_BY_ANOTHER_BANK = "65";
    public const PROMISSORY_NOTE_SIGNED_BY_A_THIRD_PARTY = "66";
    public const PROMISSORY_NOTE_SIGNED_BY_A_THIRD_PARTY_AND_ENDORSED_BY_A_BANK = "67";
    public const ONLINE_PAYMENT_SERVICE = "68";
    public const BILL_DRAWN_BY_THE_CREDITOR_ON_THE_DEBTOR = "70";
    public const BILL_DRAWN_BY_THE_CREDITOR_ON_A_BANK = "74";
    public const BILL_DRAWN_BY_THE_CREDITOR_ENDORSED_BY_ANOTHER_BANK = "75";
    public const BILL_DRAWN_BY_THE_CREDITOR_ON_A_BANK_AND_ENDORSED_BY_A_THIRD_PARTY = "76";
    public const BILL_DRAWN_BY_THE_CREDITOR_ON_A_THIRD_PARTY = "77";
    public const BILL_DRAWN_BY_CREDITOR_ON_THIRD_PARTY_ACCEPTED_AND_ENDORSED_BY_BANK = "78";
    public const NOT_TRANSFERABLE_BANKERS_DRAFT = "91";
    public const NOT_TRANSFERABLE_LOCAL_CHEQUE = "92";
    public const REFERENCE_GIRO = "93";
    public const URGENT_GIRO = "94";
    public const FREE_FORMAT_GIRO = "95";
    public const REQUESTED_METHOD_FOR_PAYMENT_WAS_NOT_USED = "96";
    public const CLEARING_BETWEEN_PARTNERS = "97";
    public const MUTUALLY_DEFINED = "ZZZ";
}
