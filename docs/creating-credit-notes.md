# Creating Credit Notes

This guide covers creating UBL credit notes for refunds, corrections, and adjustments.

## Credit Note vs Invoice

Credit notes use the `CreditNote` class instead of `Invoice`, and `CreditNoteLine` instead of `InvoiceLine`. The structure is very similar, with a few key differences:

| Invoice | Credit Note |
|---------|-------------|
| `Invoice` class | `CreditNote` class |
| `InvoiceLine` | `CreditNoteLine` |
| `setInvoiceLines()` | `setCreditNoteLines()` |
| `generator->invoice()` | `generator->creditNote()` |

## Basic Credit Note Example

```php
<?php

use NumNum\UBL\CreditNote;
use NumNum\UBL\CreditNoteLine;
use NumNum\UBL\Generator;
use NumNum\UBL\Party;
use NumNum\UBL\Address;
use NumNum\UBL\Country;
use NumNum\UBL\AccountingParty;
use NumNum\UBL\Item;
use NumNum\UBL\Price;
use NumNum\UBL\UnitCode;
use NumNum\UBL\TaxTotal;
use NumNum\UBL\TaxSubTotal;
use NumNum\UBL\TaxCategory;
use NumNum\UBL\TaxScheme;
use NumNum\UBL\LegalMonetaryTotal;
use NumNum\UBL\InvoiceTypeCode;

// 1. Create address
$country = (new Country())
    ->setIdentificationCode('BE');

$address = (new Address())
    ->setStreetName('Korenmarkt')
    ->setBuildingNumber(1)
    ->setCityName('Gent')
    ->setPostalZone('9000')
    ->setCountry($country);

// 2. Create parties
$supplierCompany = (new Party())
    ->setName('Supplier Company Name')
    ->setPostalAddress($address);

$clientCompany = (new Party())
    ->setName('Customer Company')
    ->setPostalAddress($address);

$accountingSupplierParty = (new AccountingParty())
    ->setParty($supplierCompany);

$accountingCustomerParty = (new AccountingParty())
    ->setParty($clientCompany);

// 3. Create product
$productItem = (new Item())
    ->setName('Product Name')
    ->setDescription('Product Description')
    ->setSellersItemIdentification('SKU-12345')
    ->setBuyersItemIdentification('BUYER-REF');

// 4. Create price
$price = (new Price())
    ->setBaseQuantity(1)
    ->setUnitCode(UnitCode::UNIT)
    ->setPriceAmount(100);

// 5. Create line tax total
$lineTaxTotal = (new TaxTotal())
    ->setTaxAmount(21);

// 6. Create credit note line
$creditNoteLine = (new CreditNoteLine())
    ->setId(1)
    ->setItem($productItem)
    ->setPrice($price)
    ->setTaxTotal($lineTaxTotal)
    ->setInvoicedQuantity(1);

// 7. Create tax totals
$taxScheme = (new TaxScheme())
    ->setId('VAT');

$taxCategory = (new TaxCategory())
    ->setId('S')
    ->setPercent(21)
    ->setTaxScheme($taxScheme);

$taxSubTotal = (new TaxSubTotal())
    ->setTaxableAmount(100)
    ->setTaxAmount(21)
    ->setTaxCategory($taxCategory);

$taxTotal = (new TaxTotal())
    ->setTaxAmount(21)
    ->addTaxSubTotal($taxSubTotal);

// 8. Create monetary totals
$legalMonetaryTotal = (new LegalMonetaryTotal())
    ->setLineExtensionAmount(100)
    ->setTaxExclusiveAmount(100)
    ->setTaxInclusiveAmount(121)
    ->setPayableAmount(121);

// 9. Create credit note
$creditNote = (new CreditNote())
    ->setId('CN-2024-001')
    ->setIssueDate(new \DateTime())
    ->setInvoiceTypeCode(InvoiceTypeCode::CREDIT_NOTE)
    ->setAccountingSupplierParty($accountingSupplierParty)
    ->setAccountingCustomerParty($accountingCustomerParty)
    ->setCreditNoteLines([$creditNoteLine])
    ->setTaxTotal($taxTotal)
    ->setLegalMonetaryTotal($legalMonetaryTotal);

// 10. Generate XML
$generator = new Generator();
$xml = $generator->creditNote($creditNote);

file_put_contents('credit-note.xml', $xml);
```

## Referencing the Original Invoice

When creating a credit note that references an original invoice, use `BillingReference`:

```php
use NumNum\UBL\BillingReference;
use NumNum\UBL\InvoiceDocumentReference;

$invoiceReference = (new InvoiceDocumentReference())
    ->setId('INV-2024-001')
    ->setIssueDate(new \DateTime('2024-01-15'));

$billingReference = (new BillingReference())
    ->setInvoiceDocumentReference($invoiceReference);

$creditNote = (new CreditNote())
    ->setId('CN-2024-001')
    ->setBillingReference($billingReference)
    // ... rest of credit note setup
```

## Credit Note Type Codes

```php
use NumNum\UBL\InvoiceTypeCode;

// Standard credit note
$creditNote->setInvoiceTypeCode(InvoiceTypeCode::CREDIT_NOTE);
```

## Partial Credit Notes

For partial refunds, simply include only the items being credited:

```php
// Original invoice had 10 units at €100 each
// Credit note for 3 returned units

$creditNoteLine = (new CreditNoteLine())
    ->setId(1)
    ->setItem($productItem)
    ->setPrice($price)  // €100 per unit
    ->setInvoicedQuantity(3);  // Only 3 units

$legalMonetaryTotal = (new LegalMonetaryTotal())
    ->setLineExtensionAmount(300)  // 3 × €100
    ->setTaxExclusiveAmount(300)
    ->setTaxInclusiveAmount(363)   // 300 + 21% VAT
    ->setPayableAmount(363);
```

## Multiple Credit Note Lines

```php
$creditNoteLines = [];

// Line 1: Returned product
$creditNoteLines[] = (new CreditNoteLine())
    ->setId(1)
    ->setItem((new Item())->setName('Returned Product'))
    ->setPrice((new Price())->setPriceAmount(50)->setBaseQuantity(1)->setUnitCode(UnitCode::UNIT))
    ->setInvoicedQuantity(2);

// Line 2: Service adjustment
$creditNoteLines[] = (new CreditNoteLine())
    ->setId(2)
    ->setItem((new Item())->setName('Service Credit'))
    ->setPrice((new Price())->setPriceAmount(25)->setBaseQuantity(1)->setUnitCode(UnitCode::UNIT))
    ->setInvoicedQuantity(1);

$creditNote->setCreditNoteLines($creditNoteLines);
```

## Copy Indicator

Mark a credit note as a copy:

```php
$creditNote = (new CreditNote())
    ->setId('CN-2024-001')
    ->setCopyIndicator(true)
    // ... rest of credit note setup
```

## UBL Version Settings

```php
$creditNote = (new CreditNote())
    ->setUBLVersionId('2.1')
    ->setCustomizationId('urn:cen.eu:en16931:2017')
    ->setProfileId('urn:fdc:peppol.eu:2017:poacc:billing:01:1.0')
    ->setId('CN-2024-001')
    // ... rest of credit note setup
```

## Complete Credit Note Example

See `tests/Write/SimpleCreditNoteTest.php` for a complete working example that validates against the UBL 2.1 schema.

## Common Use Cases

### Full Refund

Credit the entire original invoice amount:

```php
// Match the original invoice exactly
$creditNote = (new CreditNote())
    ->setId('CN-2024-001')
    ->setBillingReference($billingReference)  // Reference original invoice
    ->setCreditNoteLines($originalInvoiceLines) // Same lines
    ->setTaxTotal($originalTaxTotal)
    ->setLegalMonetaryTotal($originalMonetaryTotal);
```

### Price Correction

When the original invoice had incorrect pricing:

```php
// Credit the difference
$priceDifference = (new CreditNoteLine())
    ->setId(1)
    ->setItem((new Item())->setName('Price Adjustment - Product X'))
    ->setPrice((new Price())->setPriceAmount(10)->setBaseQuantity(1)->setUnitCode(UnitCode::UNIT))
    ->setInvoicedQuantity(5);  // 5 units × €10 overcharge = €50 credit
```

### Quantity Correction

When fewer items were delivered than invoiced:

```php
$quantityAdjustment = (new CreditNoteLine())
    ->setId(1)
    ->setItem($originalProduct)
    ->setPrice($originalPrice)
    ->setInvoicedQuantity(2);  // Credit for 2 undelivered items
```

## Next Steps

- [Creating Invoices](creating-invoices.md) - Standard invoice creation
- [Advanced Features](advanced-features.md) - Payment means, attachments, allowances
- [Reading UBL Files](reading-ubl-files.md) - Parse existing UBL documents
