# Contributing to ubl-invoice

Are you missing a feature and would you like to add it to the library? Great! Any contributions to this library are welcome. We try to be responsive and release new, non-breaking features as fast as possible.

## Reporting issues

This package uses the [GitHub issue tracker](https://github.com/num-num/ubl-invoice/issues) to track bugs and features. Before submitting a bug report or feature request, check to make sure it hasn't already been submitted.

## Contributing code

If you want to add additional tags, attributes or functionality to the library, please feel free to create a [pull request](https://github.com/num-num/ubl-invoice/pulls) with your changes.

Please try to follow this workflow:

- Fork the project
- Create a new branch forked from the master branch with a title for your feater (e.g. feature-that-i-want)
- Commit all your code into this branch until you are happy with your contribution
- Document your changes in the **changelog/next-release.md** file ⚠️
- If possible; try to add unit tests for your contribution
- Create a pull request with your commits

## Formatting

Please try to follow [PSR-12](https://www.php-fig.org/psr/psr-12/) rules when writing code. A PSR-12 compliant [phpcs.xml](phpcs.xml) is provided, so if your editor supports [phpcs](https://github.com/squizlabs/PHP_CodeSniffer), your editor should automatically warn you if you are deviating from PSR-12 compliant formatting.

## Unit testing

### A note on unit testing

Although unit testing is included, this repository does not provide exhaustive unit testing for *all* possibilities the library offers. This is definitely a long-term goal. So please try to add unit tests for new functionality that you add.

### Running the unit tests

To run the complete suite of unit tests

```sh
$ cd ubl-invoice
$ composer test
```

To run a single unit test

```sh
$ cd ubl-invoice
$ composer test tests/SimpleInvoiceTest
```
