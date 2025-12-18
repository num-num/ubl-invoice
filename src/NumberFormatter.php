<?php

namespace NumNum\UBL;

class NumberFormatter
{
    /**
     * Format numbers and optionally preserve decimals
     *
     * @param int|float $number
     * @param int|null $decimals
     * @param string $decimalSeparator
     * @param string $thousandsSeparator
     * @return void
     */
    public static function format($number, ?int $decimals = null, string $decimalSeparator = '.', string $thousandsSeparator = '')
    {
        if ($decimals == null) {

            $decimalPoint = '.';
            if(!is_float($number)) {
                // Convert to string to detect decimals
                // Get the current decimal point character according to the locale
                $locale = localeconv();
                $decimalPoint = $locale['decimal_point'] ?? '.';
            }

            // Convert to string to detect decimals
            $parts = explode($decimalPoint, (string)$number);

            // Count decimals, if any
            $decimals = isset($parts[1]) ? strlen(rtrim($parts[1], '0')) : 0;
        }

        return number_format($number, $decimals, $decimalSeparator, $thousandsSeparator);
    }
}
