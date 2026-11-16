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
        if ($decimals === null) {
            // Before PHP 8, float-to-string conversion uses the locale's decimal separator.
            $decimalPoint = localeconv()['decimal_point'] ?? '.';
            $numberString = str_replace($decimalPoint, '.', strtolower((string) $number));

            if (strpos($numberString, 'e') !== false) {
                [$coefficient, $exponent] = explode('e', $numberString, 2);
                $exponent = (int) $exponent;
                $decimalPosition = strpos($coefficient, '.');
                $coefficientDecimals = $decimalPosition === false
                    ? 0
                    : strlen(rtrim(substr($coefficient, $decimalPosition + 1), '0'));
                $decimals = max(0, $coefficientDecimals - $exponent);
            } else {
                $decimalPosition = strpos($numberString, '.');
                $decimals = $decimalPosition === false
                    ? 0
                    : strlen(rtrim(substr($numberString, $decimalPosition + 1), '0'));
            }
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
