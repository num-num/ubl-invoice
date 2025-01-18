<?php

require './vendor/autoload.php';

function dd()
{
    array_map(function ($x) {
        var_dump($x);
    }, func_get_args());
    die;
}


$invoiceReader = \NumNum\UBL\Reader::ubl();

$invoice = $invoiceReader->parse(file_get_contents(__DIR__ . '/tests/files/ubl-invoice-simple.xml'));

dd($invoice);
