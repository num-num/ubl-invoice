# UBL-Invoice

A modern object-oriented PHP library to create and read valid UBL and Peppol BIS 3.0 files. Please feel free to [contribute](https://github.com/num-num/ubl-invoice/pulls) if you are missing features or tags.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/num-num/ubl-invoice.svg?style=rounded-square)](https://packagist.org/packages/num-num/ubl-invoice)
[![Total Downloads](https://img.shields.io/packagist/dt/num-num/ubl-invoice.svg?style=rounded-square)](https://packagist.org/packages/num-num/ubl-invoice)

![Num•Num UBL Invoice](https://i.imgur.com/JPyFBYQ.png)

## Installation and usage

This package requires PHP 7.4 or higher and is fully compatible with PHP8. Installation can be done through [composer](https://www.getcomposer.org).

```zsh
$ composer require num-num/ubl-invoice:^2.0@alpha
```

### Creating UBL files

Please check some of the example code in the `tests/Write` folder to see how you can quickly create an UBL file.

### Reading UBL files

Reading UBL files is very straightforward:

```php
$ublReader = \NumNum\UBL\Reader::ubl();
$invoice = $ublReader->parse(file_get_contents($fileName));
var_dump($invoice); // An \NumNum\UBL\Invoice instance with filled properties!
```

Please check some additional example code in the `tests/Read` folder.

## Upgrading from package version 1.0 to 2.0

Version 2.0 of this package offers the ability to read UBL xml files and convert them into a `\NumNum\UBL\Invoice` or `\NumNum\UBL\CreditNote` + properties object structure.

To upgrade from 1.0, there are a few small, but breaking changes that need to be considered.

Please check the changelog!


## Contributing

This library is **not 100% UBL/Peppol feature-complete**, in the sense that it doesn't (yet) support **all** UBL XML tags & functionality. "Yet" being the keyword, since this definitely is the long-term goal. However, **all common UBL tags that are required to create and read most common invoices and creditnotes** are present in the library. This includes tags for discounts, cash discounts, special vat rates, etc...

If you are missing functionality, please feel free to add it :-) Adding additional tags & attributes is *very* straight-forward. Check out [CONTRIBUTING.md](CONTRIBUTING.md) for more information.

## Examples & documentation

This repository does not have a documentation website at this moment. For now, please check out some code examples by checking the unit tests in the `tests` folder.

## Changelog

A changelog is available since version v1.9.0. If you are upgrading a minor version (1.x) or major version, please check the changelog to see if you need to implement any breaking changes...
