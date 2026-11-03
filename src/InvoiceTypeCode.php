<?php

namespace NumNum\UBL;

/**
 * All possible Unit Codes that can be used
 * To extend, see also: https://docs.peppol.eu/poacc/billing/3.0/codelist/UNCL1001-inv/
 */
class InvoiceTypeCode
{
    public const INVOICE = 380;
    public const CREDIT_NOTE = 381;
    public const DEBIT_NOTE = 383;
    public const CORRECTED_INVOICE = 384;
    public const ADVANCE_INVOICE = 386;
    public const SELF_BILLING_INVOICE = 389;

    // Self-billing credit note (UNCL1001 code 261)
    // Used when the buyer issues a credit note on behalf of the seller
    public const SELF_BILLING_CREDIT_NOTE = 261;
}
