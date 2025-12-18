# Upgrading Guide

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
