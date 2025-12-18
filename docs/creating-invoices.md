# Creating Invoices

This guide covers creating UBL invoices with the `num-num/ubl-invoice` library.

## Basic Invoice Structure

Every UBL invoice requires these core components:

1. **Invoice identification** - ID, issue date, type code
2. **Parties** - Supplier and customer information
3. **Invoice lines** - Products/services with quantities and prices
4. **Tax totals** - VAT/tax calculations
5. **Monetary totals** - Line totals, tax amounts, payable amount

## Minimal Invoice Example

```php
<?php

use NumNum\UBL\Invoice;
use NumNum\UBL\Generator;
use NumNum\UBL\Party;
use NumNum\UBL\Address;
use NumNum\UBL\Country;
use NumNum\UBL\AccountingParty;
use NumNum\UBL\InvoiceLine;
use NumNum\UBL\Item;
use NumNum\UBL\Price;
use NumNum\UBL\UnitCode;
use NumNum\UBL\TaxTotal;
use NumNum\UBL\TaxSubTotal;
use NumNum\UBL\TaxCategory;
use NumNum\UBL\TaxScheme;
use NumNum\UBL\LegalMonetaryTotal;

// 1. Create country and address
$country = (new Country())
    ->setIdentificationCode('BE');

$address = (new Address())
    ->setStreetName('Korenmarkt')
    ->setBuildingNumber(1)
    ->setCityName('Gent')
    ->setPostalZone('9000')
    ->setCountry($country);

// 2. Create supplier party
$supplierCompany = (new Party())
    ->setName('Supplier Company Name')
    ->setPostalAddress($address);

$accountingSupplierParty = (new AccountingParty())
    ->setParty($supplierCompany);

// 3. Create customer party
$clientCompany = (new Party())
    ->setName('Customer Company')
    ->setPostalAddress($address);

$accountingCustomerParty = (new AccountingParty())
    ->setParty($clientCompany);

// 4. Create product item
$productItem = (new Item())
    ->setName('Product Name')
    ->setDescription('Product Description');

// 5. Create price
$price = (new Price())
    ->setBaseQuantity(1)
    ->setUnitCode(UnitCode::UNIT)
    ->setPriceAmount(100);

// 6. Create invoice line
$invoiceLine = (new InvoiceLine())
    ->setId(1)
    ->setItem($productItem)
    ->setPrice($price)
    ->setInvoicedQuantity(10);

// 7. Create tax scheme and category
$taxScheme = (new TaxScheme())
    ->setId('VAT');

$taxCategory = (new TaxCategory())
    ->setId('S')
    ->setPercent(21)
    ->setTaxScheme($taxScheme);

// 8. Create tax subtotal and total
$taxSubTotal = (new TaxSubTotal())
    ->setTaxableAmount(1000)
    ->setTaxAmount(210)
    ->setTaxCategory($taxCategory);

$taxTotal = (new TaxTotal())
    ->setTaxAmount(210)
    ->addTaxSubTotal($taxSubTotal);

// 9. Create monetary totals
$legalMonetaryTotal = (new LegalMonetaryTotal())
    ->setLineExtensionAmount(1000)
    ->setTaxExclusiveAmount(1000)
    ->setTaxInclusiveAmount(1210)
    ->setPayableAmount(1210);

// 10. Assemble the invoice
$invoice = (new Invoice())
    ->setId('INV-2024-001')
    ->setIssueDate(new \DateTime())
    ->setAccountingSupplierParty($accountingSupplierParty)
    ->setAccountingCustomerParty($accountingCustomerParty)
    ->setInvoiceLines([$invoiceLine])
    ->setTaxTotal($taxTotal)
    ->setLegalMonetaryTotal($legalMonetaryTotal);

// 11. Generate XML
$generator = new Generator();
$xml = $generator->invoice($invoice);

file_put_contents('invoice.xml', $xml);
```

## Adding Contact Information

```php
use NumNum\UBL\Contact;

$contact = (new Contact())
    ->setName('John Doe')
    ->setElectronicMail('john@example.com')
    ->setTelephone('+32 123 456 789')
    ->setTelefax('+32 123 456 780');

$clientCompany = (new Party())
    ->setName('Customer Company')
    ->setPostalAddress($address)
    ->setContact($contact);
```

## Adding Company Identifiers

```php
use NumNum\UBL\PartyIdentification;
use NumNum\UBL\PartyTaxScheme;

// VAT number
$partyTaxScheme = (new PartyTaxScheme())
    ->setCompanyId('BE0123456789')
    ->setTaxScheme($taxScheme);

// Additional identifiers (KvK, DUNS, etc.)
$partyIdentification = (new PartyIdentification())
    ->setId('12345678')
    ->setSchemeId('0106'); // KvK scheme

$supplierCompany = (new Party())
    ->setName('Supplier Company')
    ->setPostalAddress($address)
    ->setPartyIdentification($partyIdentification)
    ->setPartyTaxScheme($partyTaxScheme);
```

## Supplier Assigned Account ID

To add a customer account number (as the supplier knows the customer):

```php
$accountingCustomerParty = (new AccountingParty())
    ->setParty($clientCompany)
    ->setSupplierAssignedAccountId('CUST-10001');
```

## Invoice Periods

```php
use NumNum\UBL\InvoicePeriod;

$invoicePeriod = (new InvoicePeriod())
    ->setStartDate(new \DateTime('2024-01-01'))
    ->setEndDate(new \DateTime('2024-01-31'));

$invoiceLine = (new InvoiceLine())
    ->setId(1)
    ->setItem($productItem)
    ->setPrice($price)
    ->setInvoicedQuantity(1)
    ->setInvoicePeriod($invoicePeriod);
```

## Order References

```php
use NumNum\UBL\OrderLineReference;

$orderLineReference = (new OrderLineReference())
    ->setLineId('ORDER-123-LINE-1');

$invoiceLine = (new InvoiceLine())
    ->setId(1)
    ->setItem($productItem)
    ->setPrice($price)
    ->setInvoicedQuantity(1)
    ->setOrderLineReference($orderLineReference);
```

## Accounting Cost Codes

For cost center or project accounting:

```php
$invoiceLine = (new InvoiceLine())
    ->setId(1)
    ->setItem($productItem)
    ->setPrice($price)
    ->setInvoicedQuantity(1)
    ->setAccountingCost('Project ABC')
    ->setAccountingCostCode('PROJ-ABC-001');
```

## Multiple Tax Rates

```php
// Standard rate (21%)
$taxCategoryStandard = (new TaxCategory())
    ->setId('S')
    ->setPercent(21)
    ->setTaxScheme($taxScheme);

$taxSubTotalStandard = (new TaxSubTotal())
    ->setTaxableAmount(1000)
    ->setTaxAmount(210)
    ->setTaxCategory($taxCategoryStandard);

// Reduced rate (9%)
$taxCategoryReduced = (new TaxCategory())
    ->setId('S')
    ->setPercent(9)
    ->setTaxScheme($taxScheme);

$taxSubTotalReduced = (new TaxSubTotal())
    ->setTaxableAmount(500)
    ->setTaxAmount(45)
    ->setTaxCategory($taxCategoryReduced);

// Combined tax total
$taxTotal = (new TaxTotal())
    ->setTaxAmount(255) // 210 + 45
    ->addTaxSubTotal($taxSubTotalStandard)
    ->addTaxSubTotal($taxSubTotalReduced);
```

## Item Identifiers

```php
$productItem = (new Item())
    ->setName('Product Name')
    ->setDescription('Product Description')
    ->setSellersItemIdentification('SKU-12345')
    ->setBuyersItemIdentification('BUYER-REF-001');
```

## UBL Version and Customization

```php
$invoice = (new Invoice())
    ->setUBLVersionId('2.1')
    ->setCustomizationId('urn:cen.eu:en16931:2017')
    ->setProfileId('urn:fdc:peppol.eu:2017:poacc:billing:01:1.0')
    ->setId('INV-2024-001')
    // ... rest of invoice setup
```

## Invoice Type Codes

```php
use NumNum\UBL\InvoiceTypeCode;

// Standard invoice (default)
$invoice->setInvoiceTypeCode(InvoiceTypeCode::INVOICE);

// Other common types
$invoice->setInvoiceTypeCode(InvoiceTypeCode::DEBIT_NOTE);
$invoice->setInvoiceTypeCode(InvoiceTypeCode::SELF_BILLED_INVOICE);
```

## Copy Indicator

Mark an invoice as a copy:

```php
$invoice = (new Invoice())
    ->setId('INV-2024-001')
    ->setCopyIndicator(true)
    // ... rest of invoice setup
```

## Physical Location

Set a physical location different from postal address:

```php
$warehouseAddress = (new Address())
    ->setStreetName('Industrial Road')
    ->setBuildingNumber(100)
    ->setCityName('Antwerp')
    ->setPostalZone('2000')
    ->setCountry($country);

$supplierCompany = (new Party())
    ->setName('Supplier Company')
    ->setPostalAddress($address)
    ->setPhysicalLocation($warehouseAddress);
```

## Complete Invoice Example

See `tests/Write/SimpleInvoiceTest.php` for a complete working example that validates against the UBL 2.1 schema.

## Next Steps

- [Creating Credit Notes](creating-credit-notes.md) - For refunds and corrections
- [Advanced Features](advanced-features.md) - Payment means, attachments, allowances/charges
- [Reading UBL Files](reading-ubl-files.md) - Parse existing UBL documents
