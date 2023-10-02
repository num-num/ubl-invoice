<?php

namespace NumNum\UBL;

/**
 * All possible UNCL5305 Codes that can be used
 * To extend, see also:
 *     https://docs.peppol.eu/poacc/billing/3.0/codelist/UNCL5305/
 *     https://github.com/OpenPEPPOL/peppol-bis-invoice-3/blob/master/structure/codelist/UNCL5305.xml
 */
class UNCL5305
{
    public const UNCL5305 = 'UNCL5305';

    public const VAT_REVERSE_CHARGE = 'AE';
    public const EXEMPT_FROM_TAX = 'E';
    public const STANDARD_RATE = 'S';
    public const ZERO_RATED_GOODS = 'Z';
    public const FREE_EXPORT_ITEM = 'G';
    public const OUTSIDE_TAX_SCOPE = 'O';
    public const EEA_GOODS_AND_SERVICES = 'K';
    public const CANARY_ISLANDS_INDIRECT_TAX = 'L';
    public const CEUTA_AND_MELILLA = 'M';
    public const TRANSFERRED_VAT_ITALY = 'B';
}
