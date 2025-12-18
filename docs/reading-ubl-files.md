# Reading UBL Files

Version 2.0 of this library introduces the ability to read and parse UBL XML files into PHP objects.

## Basic Usage

```php
<?php

use NumNum\UBL\Reader;

// Create a UBL reader
$reader = Reader::ubl();

// Parse an XML file
$xml = file_get_contents('invoice.xml');
$invoice = $reader->parse($xml);

// Access invoice data
echo "Invoice ID: " . $invoice->getId() . "\n";
echo "Issue Date: " . $invoice->getIssueDate()->format('Y-m-d') . "\n";
```

## Reading Invoices

```php
$reader = Reader::ubl();
$invoice = $reader->parse(file_get_contents('invoice.xml'));

// Basic invoice information
$id = $invoice->getId();
$issueDate = $invoice->getIssueDate();
$dueDate = $invoice->getDueDate();
$invoiceTypeCode = $invoice->getInvoiceTypeCode();

// UBL metadata
$ublVersion = $invoice->getUBLVersionId();
$customizationId = $invoice->getCustomizationId();
$profileId = $invoice->getProfileId();

// Copy indicator
$isCopy = $invoice->isCopyIndicator();
```

## Reading Credit Notes

The reader automatically detects the document type:

```php
$reader = Reader::ubl();
$document = $reader->parse(file_get_contents('document.xml'));

if ($document instanceof \NumNum\UBL\CreditNote) {
    echo "This is a credit note\n";
    $lines = $document->getCreditNoteLines();
} elseif ($document instanceof \NumNum\UBL\Invoice) {
    echo "This is an invoice\n";
    $lines = $document->getInvoiceLines();
}
```

## Accessing Party Information

### Supplier Party

```php
$supplierParty = $invoice->getAccountingSupplierParty();
$supplier = $supplierParty->getParty();

echo "Supplier: " . $supplier->getName() . "\n";

// Postal address
$address = $supplier->getPostalAddress();
echo "Street: " . $address->getStreetName() . "\n";
echo "Building: " . $address->getBuildingNumber() . "\n";
echo "City: " . $address->getCityName() . "\n";
echo "Postal Code: " . $address->getPostalZone() . "\n";
echo "Country: " . $address->getCountry()->getIdentificationCode() . "\n";

// Contact information (if available)
$contact = $supplier->getContact();
if ($contact) {
    echo "Contact: " . $contact->getName() . "\n";
    echo "Email: " . $contact->getElectronicMail() . "\n";
    echo "Phone: " . $contact->getTelephone() . "\n";
}
```

### Customer Party

```php
$customerParty = $invoice->getAccountingCustomerParty();
$customer = $customerParty->getParty();

echo "Customer: " . $customer->getName() . "\n";

// Supplier-assigned account ID
$accountId = $customerParty->getSupplierAssignedAccountId();
if ($accountId) {
    echo "Account ID: " . $accountId . "\n";
}
```

## Accessing Invoice Lines

```php
$invoiceLines = $invoice->getInvoiceLines();

foreach ($invoiceLines as $line) {
    echo "Line ID: " . $line->getId() . "\n";
    echo "Quantity: " . $line->getInvoicedQuantity() . "\n";

    // Item details
    $item = $line->getItem();
    echo "Product: " . $item->getName() . "\n";
    echo "Description: " . $item->getDescription() . "\n";
    echo "Seller ID: " . $item->getSellersItemIdentification() . "\n";
    echo "Buyer ID: " . $item->getBuyersItemIdentification() . "\n";

    // Price
    $price = $line->getPrice();
    echo "Unit Price: " . $price->getPriceAmount() . "\n";
    echo "Unit Code: " . $price->getUnitCode() . "\n";

    // Line tax total (if present)
    $taxTotal = $line->getTaxTotal();
    if ($taxTotal) {
        echo "Line Tax: " . $taxTotal->getTaxAmount() . "\n";
    }

    // Accounting cost (if present)
    $accountingCost = $line->getAccountingCost();
    $accountingCostCode = $line->getAccountingCostCode();

    // Order reference (if present)
    $orderRef = $line->getOrderLineReference();
    if ($orderRef) {
        echo "Order Line: " . $orderRef->getLineId() . "\n";
    }

    echo "---\n";
}
```

## Accessing Tax Information

```php
$taxTotal = $invoice->getTaxTotal();

echo "Total Tax: " . $taxTotal->getTaxAmount() . "\n";

// Tax breakdown by category
$taxSubTotals = $taxTotal->getTaxSubTotals();

foreach ($taxSubTotals as $subTotal) {
    $category = $subTotal->getTaxCategory();

    echo "Tax Category: " . $category->getId() . "\n";
    echo "Tax Rate: " . $category->getPercent() . "%\n";
    echo "Taxable Amount: " . $subTotal->getTaxableAmount() . "\n";
    echo "Tax Amount: " . $subTotal->getTaxAmount() . "\n";

    $scheme = $category->getTaxScheme();
    echo "Tax Scheme: " . $scheme->getId() . "\n";
    echo "---\n";
}
```

## Accessing Monetary Totals

```php
$monetaryTotal = $invoice->getLegalMonetaryTotal();

echo "Line Extension Amount: " . $monetaryTotal->getLineExtensionAmount() . "\n";
echo "Tax Exclusive Amount: " . $monetaryTotal->getTaxExclusiveAmount() . "\n";
echo "Tax Inclusive Amount: " . $monetaryTotal->getTaxInclusiveAmount() . "\n";
echo "Allowance Total: " . $monetaryTotal->getAllowanceTotalAmount() . "\n";
echo "Charge Total: " . $monetaryTotal->getChargeTotalAmount() . "\n";
echo "Prepaid Amount: " . $monetaryTotal->getPrepaidAmount() . "\n";
echo "Payable Amount: " . $monetaryTotal->getPayableAmount() . "\n";
```

## Accessing Payment Information

```php
$paymentMeans = $invoice->getPaymentMeans();

if ($paymentMeans) {
    echo "Payment Code: " . $paymentMeans->getPaymentMeansCode() . "\n";
    echo "Payment ID: " . $paymentMeans->getPaymentId() . "\n";

    // Bank account details
    $account = $paymentMeans->getPayeeFinancialAccount();
    if ($account) {
        echo "IBAN: " . $account->getId() . "\n";
        echo "Account Name: " . $account->getName() . "\n";

        $branch = $account->getFinancialInstitutionBranch();
        if ($branch) {
            echo "BIC: " . $branch->getId() . "\n";
        }
    }
}
```

## Accessing Attachments

```php
$additionalDocuments = $invoice->getAdditionalDocumentReferences();

foreach ($additionalDocuments as $docRef) {
    echo "Document ID: " . $docRef->getId() . "\n";
    echo "Document Type: " . $docRef->getDocumentType() . "\n";

    $attachment = $docRef->getAttachment();
    if ($attachment) {
        // Embedded content
        $base64Content = $attachment->getBase64Content();
        $mimeCode = $attachment->getMimeCode();
        $filename = $attachment->getFilename();

        if ($base64Content) {
            echo "Embedded file: " . $filename . " (" . $mimeCode . ")\n";
            $decodedContent = base64_decode($base64Content);
        }

        // External reference
        $externalRef = $attachment->getExternalReference();
        if ($externalRef) {
            echo "External URI: " . $externalRef->getUri() . "\n";
        }
    }
}
```

## Working with Invoice Periods

```php
$invoiceLines = $invoice->getInvoiceLines();

foreach ($invoiceLines as $line) {
    $period = $line->getInvoicePeriod();

    if ($period) {
        $startDate = $period->getStartDate();
        $endDate = $period->getEndDate();

        echo "Service Period: ";
        echo $startDate->format('Y-m-d');
        echo " to ";
        echo $endDate ? $endDate->format('Y-m-d') : 'ongoing';
        echo "\n";
    }
}
```

## Error Handling

```php
try {
    $reader = Reader::ubl();
    $document = $reader->parse($xmlContent);
} catch (\Exception $e) {
    echo "Failed to parse UBL document: " . $e->getMessage() . "\n";
}
```

## Complete Example

```php
<?php

use NumNum\UBL\Reader;

$reader = Reader::ubl();
$invoice = $reader->parse(file_get_contents('invoice.xml'));

// Print invoice summary
echo "=== INVOICE SUMMARY ===\n";
echo "Invoice ID: " . $invoice->getId() . "\n";
echo "Issue Date: " . $invoice->getIssueDate()->format('Y-m-d') . "\n";

echo "\n=== SUPPLIER ===\n";
$supplier = $invoice->getAccountingSupplierParty()->getParty();
echo "Name: " . $supplier->getName() . "\n";

echo "\n=== CUSTOMER ===\n";
$customer = $invoice->getAccountingCustomerParty()->getParty();
echo "Name: " . $customer->getName() . "\n";

echo "\n=== LINE ITEMS ===\n";
foreach ($invoice->getInvoiceLines() as $line) {
    $item = $line->getItem();
    $price = $line->getPrice();
    $qty = $line->getInvoicedQuantity();
    $lineTotal = $price->getPriceAmount() * $qty;

    printf("%-30s %5d × %8.2f = %10.2f\n",
        $item->getName(),
        $qty,
        $price->getPriceAmount(),
        $lineTotal
    );
}

echo "\n=== TOTALS ===\n";
$totals = $invoice->getLegalMonetaryTotal();
echo "Subtotal: " . number_format($totals->getTaxExclusiveAmount(), 2) . "\n";
echo "Tax: " . number_format($invoice->getTaxTotal()->getTaxAmount(), 2) . "\n";
echo "Total: " . number_format($totals->getPayableAmount(), 2) . "\n";
```

## Next Steps

- [Creating Invoices](creating-invoices.md) - Create new UBL invoices
- [Creating Credit Notes](creating-credit-notes.md) - Create credit notes
- [Advanced Features](advanced-features.md) - Payment means, attachments, and more
