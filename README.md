# UBL-Invoice

A modern object-oriented PHP library to **create** and **read** valid UBL and Peppol BIS 3.0 files. Please feel free to [contribute](https://github.com/num-num/ubl-invoice/pulls) if you are missing features or tags.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/num-num/ubl-invoice.svg?style=rounded-square)](https://packagist.org/packages/num-num/ubl-invoice)
[![Total Downloads](https://img.shields.io/packagist/dt/num-num/ubl-invoice.svg?style=rounded-square)](https://packagist.org/packages/num-num/ubl-invoice)


![Num•Num UBL Invoice](https://i.imgur.com/JPyFBYQ.png)

## Installation and usage

This package is fully compatible with **PHP 8.x** and also supports **PHP 7.4**. You can install it using [Composer](https://www.getcomposer.org).
```zsh
$ composer require num-num/ubl-invoice:^2.0@beta
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

## Upgrading from package version 1.0 to 2.0

Version 2.0 of this package offers the ability to read UBL xml files and convert them into a `\NumNum\UBL\Invoice` or `\NumNum\UBL\CreditNote` + properties object structure.

To upgrade from 1.0, there are a few small, but breaking changes that need to be considered.

### New `AccountingParty` wrapper class

The most significant change is that `setAccountingSupplierParty()` and `setAccountingCustomerParty()` now require an `AccountingParty` object instead of a `Party` object directly.

**Before (v1.x):**
```php
$supplierParty = (new \NumNum\UBL\Party())
    ->setName('Supplier Company Name')
    ->setPostalAddress($address);

$clientParty = (new \NumNum\UBL\Party())
    ->setName('My client')
    ->setPostalAddress($address);

$invoice = (new \NumNum\UBL\Invoice())
    ->setAccountingSupplierParty($supplierParty)
    ->setAccountingCustomerParty($clientParty)
    ->setSupplierAssignedAccountID('10001'); // Was on Invoice
```

**After (v2.0):**
```php
$supplierParty = (new \NumNum\UBL\Party())
    ->setName('Supplier Company Name')
    ->setPostalAddress($address);

$clientParty = (new \NumNum\UBL\Party())
    ->setName('My client')
    ->setPostalAddress($address);

// Wrap Party objects in AccountingParty
$accountingSupplierParty = (new \NumNum\UBL\AccountingParty())
    ->setParty($supplierParty);

$accountingCustomerParty = (new \NumNum\UBL\AccountingParty())
    ->setParty($clientParty)
    ->setSupplierAssignedAccountId('10001'); // Now on AccountingParty

$invoice = (new \NumNum\UBL\Invoice())
    ->setAccountingSupplierParty($accountingSupplierParty)
    ->setAccountingCustomerParty($accountingCustomerParty);
```

### `accountingCustomerPartyContact` moved to `AccountingParty`

If you were using `Invoice::accountingCustomerPartyContact`, use `AccountingParty::setAccountingContact()` instead:

**Before (v1.x):**
```php
$invoice->accountingCustomerPartyContact($contact);
```

**After (v2.0):**
```php
$accountingCustomerParty = (new \NumNum\UBL\AccountingParty())
    ->setParty($clientParty)
    ->setAccountingContact($contact);
```

### Attachment `setFileStream` renamed to `setBase64Content`

**Before (v1.x):**
```php
$attachment->setFileStream($base64Content);
$content = $attachment->getFileStream();
```

**After (v2.0):**
```php
$attachment->setBase64Content($base64Content);
$content = $attachment->getBase64Content();
```

### Function naming consistency (ID → Id)

All functions with `ID` suffix have been renamed to use `Id` for consistency:

| Before (v1.x) | After (v2.0) |
|---------------|--------------|
| `setUBLVersionID()` / `getUBLVersionID()` | `setUBLVersionId()` / `getUBLVersionId()` |
| `setCustomizationID()` / `getCustomizationID()` | `setCustomizationId()` / `getCustomizationId()` |
| `setProfileID()` / `getProfileID()` | `setProfileId()` / `getProfileId()` |
| `setSchemeID()` / `getSchemeID()` | `setSchemeId()` / `getSchemeId()` |
| `setUnitCodeListID()` / `getUnitCodeListID()` | `setUnitCodeListId()` / `getUnitCodeListId()` |
| `setSupplierAssignedAccountID()` / `getSupplierAssignedAccountID()` | `setSupplierAssignedAccountId()` / `getSupplierAssignedAccountId()` |

### Quick migration checklist

- [ ] Wrap `Party` objects in `AccountingParty` for supplier/customer parties
- [ ] Move `setSupplierAssignedAccountID()` from `Invoice` to `AccountingParty` and rename to `setSupplierAssignedAccountId()`
- [ ] Replace `accountingCustomerPartyContact()` with `AccountingParty::setAccountingContact()`
- [ ] Rename `setFileStream()`/`getFileStream()` to `setBase64Content()`/`getBase64Content()` on Attachments
- [ ] Update all `*ID()` method calls to use `*Id()` naming


## Contributing - bug reporting

This library is **not 100% UBL/Peppol feature-complete**, in the sense that it doesn't (yet) support **all** UBL XML tags & functionality. "Yet" being the keyword, since this definitely is the long-term goal. However, **all common UBL tags that are required to create and read most common invoices and creditnotes** are present in the library. This includes tags for discounts, cash discounts, special vat rates, etc...

If you are missing functionality, please feel free to add it :-) Adding additional tags & attributes is *very* straight-forward. Check out [CONTRIBUTING.md](CONTRIBUTING.md) for more information.

Are you experiencing a bug? Please feel free to open an issue in the issue tracker!

## Examples & documentation

This repository does not have a documentation website at this moment. For now, please check out some code examples by checking the unit tests in the `tests` folder.

## Changelog

A changelog is available since version v1.9.0. If you are upgrading a minor version (1.x) or major version, please check the changelog to see if you need to implement any breaking changes...
