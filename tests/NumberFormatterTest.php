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
        return $this->assertEqualsCanonicalizing($formattedNumber, $result);
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
        ];
    }
}
