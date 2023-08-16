<?php

namespace NumNum\UBL;

/**
 * All possible Unit Codes that can be used
 * To extend, see also: http://tfig.unece.org/contents/recommendation-20.htm
 */
class InvoiceTypeCode
{
    const INVOICE = 380;
    const CREDIT_NOTE = 381;
    const DEBIT_NOTE = 383;
    const CORRECTED_INVOICE = 384;
    const ADVANCE_INVOICE = 386;
    const SELF_BILLING_INVOICE = 389;
}
