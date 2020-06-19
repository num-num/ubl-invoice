# UBL-Invoice

A modern object-oriented PHP library to create valid UBL files. Feel free to [contribute](https://github.com/num-num/ubl-invoice/pulls) if you are missing features.

## Installation and usage

This package requires PHP 7.0 or higher. Installation can be done through [composer](https://www.getcomposer.org).

```sh
$ composer require num-num/ubl-invoice
```

## Contributing

This library is not 100% UBL feature-complete, in the sense that it doesn't (yet) support **all** UBL XML tags & functionality. "Yet" being the keyword, since this defintely is the long-term goal. All common UBL tags that are required for most invoices are present in the library. This includes tags for discounts, cash discounts, special vat rates, etc...

If you require additional UBL tags or extra functionality, please feel free to create a [pull request](https://github.com/num-num/ubl-invoice/pulls?q=is%3Apr+is%3Aclosed) to merge your code additions or changes. Please always follow [the PSR-12 coding standard](https://www.php-fig.org/psr/psr-12/). You can use [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) in your editor to ensure you are following this standard, since a PSR-12 compliant `phpcs.xml` file is included in the source code.

## Examples & documentation

This repository does not have a documentation website at this moment. For now, you can find a few simple code examples in the `tests` folder.

## Unit testing

Although unit testing is included, this repository does not provide exhaustive unit testing for *all* possiblities, getters & setters that are included in the code. Please feel free to add new unit tests for new features that you write. Unit tests are to be created in the `tests` folder. can be run by running `composer test` in the repository root.

### Running the unit tests

```sh
$ cd ubl-invoice
$ composer test
```
