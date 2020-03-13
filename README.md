# UBL-Invoice

A PHP Wrapper for creating UBL invoices. This code is a hard fork of the [`cleverit/UBL_invoice`](https://github.com/CleverIT/UBL_invoice) package. Feel free to contribute if you are missing features.

## Installation and usage

This package requires PHP 7.0 or higher. Installation can be done with [composer](https://www.getcomposer.org).

```bash
composer require num-num/ubl-invoice
```

## Contributing

Feel free to create a pull request for code additions/changes. Please always follow [the PSR-2 coding standard](https://www.php-fig.org/psr/psr-2/). You can use [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) in your editor to ensure this, since a `phpcs.xml` file is included with this project.

## Examples & Documentation

This repository does not contain any documentation at the moment. For now, you can find a fairly simple example in the `SimpleInvoiceTest.php` file in the `tests` unit tests folder.

## Unit testing

This repository does not contain contain exhaustive unit testing for all possiblities and options that are included in the code. Unit tests are to be created in the `tests` folder and can be run by running `phpunit` in the repository root.
