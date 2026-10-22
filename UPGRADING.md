# Upgrading Guide

## Upgrading from package version 2.x to 3.0

### Stricter types and new required PHP extensions

Version 3.0 adds native type declarations throughout the library. Most code is unaffected, but three changes can break existing projects:

**Typed public and protected properties.** The following properties now carry a native type:

| Property | Type |
|----------|------|
| `$xmlTagName` (Invoice, CreditNote, DebitNote, their line classes, PaymentMeans, PaymentMandate) | `string` |
| `$invoiceTypeCode` (Invoice, CreditNote, DebitNote) | `?int` |
| `InvoiceLine::$invoicedQuantity` | `?float` |
| `Invoice::$invoiceLines` | `?array` |
| `Generator::$currencyID` / `Reader::$currencyID` | `string` |

A subclass that redeclares one of these properties without the same type now causes a fatal error, and assigning a value of another type throws a `TypeError`. `Generator::$currencyID` and `Reader::$currencyID` also default to `'EUR'` instead of `null` and can no longer be set to `null`.

**Stricter parameter types.** `Generator::invoice()`, `Generator::creditNote()`, `Generator::debitNote()` and `Reader::ubl()` now declare their `$currencyId` parameter as `string`, and most setters declare native parameter types. Passing `null` or an object of another type where it previously happened to work now throws a `TypeError`.

**New required PHP extensions.** `composer.json` now requires `ext-libxml` (reading large UBL files) and `ext-fileinfo` (detecting the mime type of file attachments). Both are enabled by default in most PHP builds, but slim Docker images may need them added.

### Quick migration checklist

- [ ] If you extend library classes and redeclare `$xmlTagName`, `$invoiceTypeCode`, `$invoicedQuantity` or `$invoiceLines`, add the same type declarations
- [ ] Make sure the `libxml` and `fileinfo` PHP extensions are enabled

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
