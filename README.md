# UBL-Invoice

A modern object-oriented PHP library to create valid UBL files. Please feel free to [contribute](https://github.com/num-num/ubl-invoice/pulls) if you are missing features or tags.

## Installation and usage

This package requires PHP 7.0 or higher. Installation can be done through [composer](https://www.getcomposer.org).

```sh
$ composer require num-num/ubl-invoice
```

## Contributing

This library is not 100% UBL feature-complete, in the sense that it doesn't (yet) support **all** UBL XML tags & functionality. "Yet" being the keyword, since this definitely is the long-term goal. All common UBL tags that are required for most invoices are present in the library. This includes tags for discounts, cash discounts, special vat rates, etc...

If you are missing functionality, please feel free to add it :-) Adding additional tags & attributes is fairly straight-forward. Check out [CONTRIBUTING.md](CONTRIBUTING.md) for more information.

## Examples & documentation

This repository does not have a documentation website at this moment. For now, please check out some code examples by checking the unit tests in the `tests` folder.

## Changelog

A changelog is available since version v1.9.0. If you are upgrading a minor version (1.x) or major version, please check the changelog to see if you need to implement any breaking changes...

