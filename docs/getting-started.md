# Getting Started with UBL-Invoice

A quick guide to creating and reading UBL (Universal Business Language) invoice and credit note files using PHP.

## What is UBL?

**UBL** (Universal Business Language) is an international XML standard for electronic business documents. It's used for:

- B2B electronic invoicing
- Government procurement (required in EU countries)
- Cross-border invoicing via Peppol network
- Automated invoice processing

## Installation

**Requirements:** PHP 7.4+ or PHP 8.x

```bash
composer require num-num/ubl-invoice
```

## Quick Start: Creating an Invoice

```php
<?php

require 'vendor/autoload.php';

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

// 1. Create address
$country = (new Country())->setIdentificationCode('BE');
$address = (new Address())
    ->setStreetName('Main Street')
    ->setBuildingNumber(1)
    ->setCityName('Brussels')
    ->setPostalZone('1000')
    ->setCountry($country);

// 2. Create supplier and customer
$supplier = (new AccountingParty())
    ->setParty((new Party())->setName('My Company')->setPostalAddress($address));

$customer = (new AccountingParty())
    ->setParty((new Party())->setName('Customer Company')->setPostalAddress($address));

// 3. Create invoice line
$invoiceLine = (new InvoiceLine())
    ->setId(1)
    ->setInvoicedQuantity(10)
    ->setItem((new Item())->setName('Consulting Services'))
    ->setPrice((new Price())->setBaseQuantity(1)->setUnitCode(UnitCode::UNIT)->setPriceAmount(100));

// 4. Create tax totals
$taxScheme = (new TaxScheme())->setId('VAT');
$taxCategory = (new TaxCategory())->setId('S')->setPercent(21)->setTaxScheme($taxScheme);
$taxSubTotal = (new TaxSubTotal())->setTaxableAmount(1000)->setTaxAmount(210)->setTaxCategory($taxCategory);
$taxTotal = (new TaxTotal())->setTaxAmount(210)->addTaxSubTotal($taxSubTotal);

// 5. Create monetary totals
$monetaryTotal = (new LegalMonetaryTotal())
    ->setLineExtensionAmount(1000)
    ->setTaxExclusiveAmount(1000)
    ->setPayableAmount(1210);

// 6. Assemble invoice
$invoice = (new Invoice())
    ->setId('INV-2024-001')
    ->setIssueDate(new \DateTime())
    ->setAccountingSupplierParty($supplier)
    ->setAccountingCustomerParty($customer)
    ->setInvoiceLines([$invoiceLine])
    ->setTaxTotal($taxTotal)
    ->setLegalMonetaryTotal($monetaryTotal);

// 7. Generate XML
$generator = new Generator();
$xml = $generator->invoice($invoice);

file_put_contents('invoice.xml', $xml);
```

## Quick Start: Reading a UBL File

```php
<?php

require 'vendor/autoload.php';

use NumNum\UBL\Reader;

$reader = Reader::ubl();
$invoice = $reader->parse(file_get_contents('invoice.xml'));

echo "Invoice ID: " . $invoice->getId() . "\n";
echo "Issue Date: " . $invoice->getIssueDate()->format('Y-m-d') . "\n";
echo "Supplier: " . $invoice->getAccountingSupplierParty()->getParty()->getName() . "\n";
echo "Customer: " . $invoice->getAccountingCustomerParty()->getParty()->getName() . "\n";
echo "Total: " . $invoice->getLegalMonetaryTotal()->getPayableAmount() . "\n";
```

## Next Steps

- [Creating Invoices](creating-invoices.md) - Detailed invoice creation guide
- [Creating Credit Notes](creating-credit-notes.md) - How to create credit notes
- [Reading UBL Files](reading-ubl-files.md) - Parsing existing UBL documents
- [Advanced Features](advanced-features.md) - Payment means, attachments, EN16931 compliance

## Additional Resources

- [UBL 2.1 Specification](http://docs.oasis-open.org/ubl/UBL-2.1.html)
- [Peppol BIS Billing 3.0](https://docs.peppol.eu/poacc/billing/3.0/)
- Check the `tests/Write` folder for more code examples
