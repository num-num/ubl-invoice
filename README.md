# UBL-Invoice

A modern object-oriented PHP library to **create** and **read** valid UBL and Peppol BIS 3.0 files. Please feel free to [contribute](https://github.com/num-num/ubl-invoice/pulls) if you are missing features or tags.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/num-num/ubl-invoice.svg?style=rounded-square)](https://packagist.org/packages/num-num/ubl-invoice)
[![Total Downloads](https://img.shields.io/packagist/dt/num-num/ubl-invoice.svg?style=rounded-square)](https://packagist.org/packages/num-num/ubl-invoice)


![Num•Num UBL Invoice](https://i.imgur.com/JPyFBYQ.png)

## Installation and usage

This package is fully compatible with **PHP 8.x** and also supports **PHP 7.4**. You can install it using [Composer](https://www.getcomposer.org).
```zsh
$ composer require num-num/ubl-invoice
```

#### Creating UBL files

```php
$invoice = (new \NumNum\UBL\Invoice())
    ->setUBLVersionId('2.4')
    ->setId(123);
    // ... etc, all other props you need

$generator = new \NumNum\UBL\Generator();
$ublXml = $generator->invoice($invoice);
```

Please check some of the example code in the `tests/Write` folder to see how you can quickly create an UBL file and use all included properties.

#### Reading UBL files ✨

Need to quickly read UBL files? As of version 2.0, this library now supports UBL file reading. It's simple and easy to use:

```php
$ublReader = \NumNum\UBL\Reader::ubl();
$invoice = $ublReader->parse(file_get_contents($fileName));
var_dump($invoice); // An \NumNum\UBL\Invoice instance with filled properties!
```

Please check some additional example code in the `tests/Read` folder.

## Upgrading

If you are upgrading from version 1.x to 2.0, please check the [UPGRADING.md](UPGRADING.md) guide for breaking changes and migration instructions.

## Contributing - bug reporting

This library is **not 100% UBL/Peppol feature-complete**, in the sense that it doesn't (yet) support **all** UBL XML tags & functionality. "Yet" being the keyword, since this definitely is the long-term goal. However, **all common UBL tags that are required to create and read most common invoices and creditnotes** are present in the library. This includes tags for discounts, cash discounts, special vat rates, etc...

If you are missing functionality, please feel free to add it :-) Adding additional tags & attributes is *very* straight-forward. Check out [CONTRIBUTING.md](CONTRIBUTING.md) for more information.

Are you experiencing a bug? Please feel free to open an issue in the issue tracker!

## Documentation

- [Getting Started](docs/getting-started.md) - Quick start guide with installation and basic examples
- [Creating Invoices](docs/creating-invoices.md) - Detailed invoice creation guide
- [Creating Credit Notes](docs/creating-credit-notes.md) - Credit note creation guide
- [Reading UBL Files](docs/reading-ubl-files.md) - Parsing existing UBL documents
- [Advanced Features](docs/advanced-features.md) - Payment means, attachments, EN16931 compliance

For additional examples, check the unit tests in the `tests` folder.

## Changelog

Since version 2.0, all changelog information can be found on the [GitHub Releases](https://github.com/num-num/ubl-invoice/releases) page.
