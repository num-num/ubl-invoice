<?php

namespace NumNum\UBL\Tests;

use NumNum\UBL\NumberFormatter;
use PHPUnit\Framework\TestCase;

/**
 * Test the NumberFormatter class
 */
class NumberFormatterTest extends TestCase
{
    /**
     * @dataProvider formattedNumbersProvider
     */
    public function testNumberFormatterRounding($number, string $formattedNumber, ?int $decimals)
    {
        $result = NumberFormatter::format($number, $decimals);
        $this->assertEqualsCanonicalizing($formattedNumber, $result);
    }

    /**
     * @dataProvider formattedNumbersProvider
     */
    public function testNumberFormatterWithCommaLocale($number, string $formattedNumber, ?int $decimals)
    {
        $originalLocale = setlocale(LC_NUMERIC, 0);

        try {
            $locale = setlocale(LC_NUMERIC, 'de_DE.UTF-8', 'de_DE.utf8', 'de_DE', 'German_Germany.1252');
            if ($locale === false || localeconv()['decimal_point'] !== ',') {
                $this->markTestSkipped('A locale with a decimal comma is not available.');
            }

            $this->assertSame($formattedNumber, NumberFormatter::format($number, $decimals));
        } finally {
            setlocale(LC_NUMERIC, $originalLocale);
        }
    }

    public function formattedNumbersProvider(): array
    {
        return [
            [0.0,       '0',         null],
            [0.1,       '0.1',       null],
            [0.1200500, '0.12005',   null],
            [1.2345678, '1.2345678', null],
            [1.236789,  '1.236789',  null],
            [1.236789,  '1.24',      2],
            [1,         '1.00',      2],
            [1.000,     '1',         null],
            [12.345,    '12.345',    null],
            [0.125,     '0.125',     null],
            [12.345,    '12.35',     2],

            // Preserve small values represented in scientific notation.
            [1.0e-5,   '0.00001',   null],
            [1.23e-5,  '0.0000123', null],
            [-1.0e-5,  '-0.00001',  null],
            [1.0e-5,   '0.00',      2],

            // An explicit 0 must round to a whole number, not fall back
            // to auto-detecting the decimals present in the input
            [12.345,    '12',        0],
            [0.5,       '1',         0],
            [1.0,       '1',         0],
            [0.0,       '0',         0],
        ];
    }
}
