<?php

namespace NumNum\UBL;

/**
 * All possible Unit Codes that can be used
 * To extend, see also: http://tfig.unece.org/contents/recommendation-20.htm
 */
class UnitCode
{
    public const UNIT = 'C62';
    public const PIECE = 'H87';

    public const ARE = 'ARE';
    public const HECTARE = 'HAR';

    public const SQUARE_METRE = 'MTK';
    public const SQUARE_KILOMETRE = 'KMK';
    public const SQUARE_FOOT = 'FTK';
    public const SQUARE_YARD = 'YDK';
    public const SQUARE_MILE = 'MIK';

    public const LITRE = 'LTR';

    public const SECOND = 'SEC';
    public const MINUTE = 'MIN';
    public const HOUR = 'HUR';
    public const DAY = 'DAY';
    public const MONTH = 'MON';
    public const YEAR = 'ANN';
}
