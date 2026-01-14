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
    public static function format(
        $number,
        ?int $decimals = null,
        string $decimalSeparator = ".",
        string $thousandsSeparator = ""
    ) {
        if ($decimals == null) {
            // Get the current decimal point character according to the locale
            // This is needed because (string)$number uses the locale's decimal separator
            $locale = localeconv();
            $decimalPoint = $locale["decimal_point"] ?? ".";

            // Convert to string to detect decimals
            $parts = explode($decimalPoint, (string) $number);

            // Count decimals, if any
            $decimals = isset($parts[1]) ? strlen(rtrim($parts[1], "0")) : 0;
        }

        $value = number_format(
            $number,
            $decimals,
            $decimalSeparator,
            $thousandsSeparator
        );

        return $value;
    }
}
