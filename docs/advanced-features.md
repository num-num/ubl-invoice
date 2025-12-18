# Advanced Features

This guide covers advanced features of the UBL library including payment configuration, attachments, allowances/charges, and compliance settings.

## UBL Version Configuration

```php
use NumNum\UBL\Invoice;

$invoice = (new Invoice())
    ->setUBLVersionId('2.1')
    ->setCustomizationId('urn:cen.eu:en16931:2017')
    ->setProfileId('urn:fdc:peppol.eu:2017:poacc:billing:01:1.0');
```

### Common Profile IDs

| Profile | Description |
|---------|-------------|
| `urn:fdc:peppol.eu:2017:poacc:billing:01:1.0` | Peppol BIS Billing 3.0 |
| `urn:cen.eu:en16931:2017` | EN16931 European Standard |

## Payment Means

### Bank Transfer (SEPA)

```php
use NumNum\UBL\PaymentMeans;
use NumNum\UBL\FinancialInstitutionBranch;
use NumNum\UBL\PayeeFinancialAccount;

// Bank account
$financialInstitution = (new FinancialInstitutionBranch())
    ->setId('KREDBEBB');  // BIC code

$payeeAccount = (new PayeeFinancialAccount())
    ->setId('BE68539007547034')  // IBAN
    ->setName('Company Account')
    ->setFinancialInstitutionBranch($financialInstitution);

// Payment means
$paymentMeans = (new PaymentMeans())
    ->setPaymentMeansCode(30)  // 30 = Credit transfer
    ->setPaymentId('INV-2024-001')
    ->setPayeeFinancialAccount($payeeAccount);

$invoice->setPaymentMeans($paymentMeans);
```

### Common Payment Means Codes

| Code | Description |
|------|-------------|
| 10 | Cash |
| 20 | Cheque |
| 30 | Credit transfer |
| 31 | Debit transfer |
| 42 | Payment to bank account |
| 48 | Bank card |
| 49 | Direct debit |
| 57 | Standing agreement |
| 58 | SEPA credit transfer |
| 59 | SEPA direct debit |

## Payment Terms

```php
use NumNum\UBL\PaymentTerms;

$paymentTerms = (new PaymentTerms())
    ->setNote('Net 30 days');

$invoice->setPaymentTerms($paymentTerms);
```

## Document References

### Order Reference

```php
$invoice->setOrderReference('PO-2024-001');
```

### Billing Reference (for Credit Notes)

```php
use NumNum\UBL\BillingReference;
use NumNum\UBL\InvoiceDocumentReference;

$invoiceRef = (new InvoiceDocumentReference())
    ->setId('INV-2024-001')
    ->setIssueDate(new \DateTime('2024-01-15'));

$billingReference = (new BillingReference())
    ->setInvoiceDocumentReference($invoiceRef);

$creditNote->setBillingReference($billingReference);
```

## Attachments

### Embedded Attachment (Base64)

```php
use NumNum\UBL\AdditionalDocumentReference;
use NumNum\UBL\Attachment;

$pdfContent = file_get_contents('timesheet.pdf');

$attachment = (new Attachment())
    ->setBase64Content(base64_encode($pdfContent))
    ->setMimeCode('application/pdf')
    ->setFilename('timesheet.pdf');

$docReference = (new AdditionalDocumentReference())
    ->setId('ATT-001')
    ->setDocumentType('Timesheet')
    ->setAttachment($attachment);

$invoice->addAdditionalDocumentReference($docReference);
```

### External Reference (URL)

```php
use NumNum\UBL\ExternalReference;

$externalRef = (new ExternalReference())
    ->setUri('https://example.com/documents/timesheet.pdf');

$attachment = (new Attachment())
    ->setExternalReference($externalRef);

$docReference = (new AdditionalDocumentReference())
    ->setId('ATT-002')
    ->setDocumentType('Supporting Document')
    ->setAttachment($attachment);

$invoice->addAdditionalDocumentReference($docReference);
```

## Allowances and Charges

### Document Level Allowance (Discount)

```php
use NumNum\UBL\AllowanceCharge;

$discount = (new AllowanceCharge())
    ->setChargeIndicator(false)  // false = allowance/discount
    ->setAllowanceChargeReason('Volume discount')
    ->setAmount(50.00)
    ->setTaxCategory($taxCategory);

$invoice->addAllowanceCharge($discount);
```

### Document Level Charge

```php
$handlingFee = (new AllowanceCharge())
    ->setChargeIndicator(true)  // true = charge
    ->setAllowanceChargeReason('Handling fee')
    ->setAmount(10.00)
    ->setTaxCategory($taxCategory);

$invoice->addAllowanceCharge($handlingFee);
```

### Line Level Allowance

```php
$lineDiscount = (new AllowanceCharge())
    ->setChargeIndicator(false)
    ->setAllowanceChargeReason('Special price')
    ->setAmount(5.00);

$invoiceLine->addAllowanceCharge($lineDiscount);
```

## Legal Entity Information

```php
use NumNum\UBL\LegalEntity;

$legalEntity = (new LegalEntity())
    ->setRegistrationName('Company Legal Name BV')
    ->setCompanyId('0123456789');

$party = (new Party())
    ->setName('Company Name')
    ->setPostalAddress($address)
    ->setLegalEntity($legalEntity);
```

## Party Tax Scheme

```php
use NumNum\UBL\PartyTaxScheme;

$partyTaxScheme = (new PartyTaxScheme())
    ->setCompanyId('BE0123456789')
    ->setTaxScheme($taxScheme);

$party = (new Party())
    ->setName('Company Name')
    ->setPostalAddress($address)
    ->setPartyTaxScheme($partyTaxScheme);
```

## Party Identification

```php
use NumNum\UBL\PartyIdentification;

// GLN (Global Location Number)
$gln = (new PartyIdentification())
    ->setId('5412345000013')
    ->setSchemeId('0088');

// KvK number (Dutch)
$kvk = (new PartyIdentification())
    ->setId('12345678')
    ->setSchemeId('0106');

$party = (new Party())
    ->setName('Company Name')
    ->setPostalAddress($address)
    ->setPartyIdentification($gln);
```

### Common Scheme IDs

| Scheme ID | Description |
|-----------|-------------|
| 0002 | System Information et Repertoire des Entreprise et des Etablissements (SIRENE) |
| 0007 | Organisationsnummer (Swedish) |
| 0009 | SIRET-CODE |
| 0088 | EAN Location Code (GLN) |
| 0096 | DANISH CHAMBER OF COMMERCE Scheme |
| 0106 | Netherlands Chamber of Commerce (KvK) |
| 0184 | DIGSTORG |
| 0190 | Dutch Originator's Identification Number (OIN) |
| 9925 | Belgian enterprise number (KBO/BCE) |

## Delivery Information

```php
use NumNum\UBL\Delivery;
use NumNum\UBL\DeliveryLocation;

$deliveryLocation = (new DeliveryLocation())
    ->setAddress($deliveryAddress);

$delivery = (new Delivery())
    ->setActualDeliveryDate(new \DateTime('2024-02-01'))
    ->setDeliveryLocation($deliveryLocation);

$invoice->setDelivery($delivery);
```

## Tax Exemption

```php
$taxCategory = (new TaxCategory())
    ->setId('E')  // E = Exempt
    ->setPercent(0)
    ->setTaxExemptionReason('Export outside EU')
    ->setTaxExemptionReasonCode('VATEX-EU-G')
    ->setTaxScheme($taxScheme);
```

### Common Tax Category IDs

| ID | Description |
|----|-------------|
| S | Standard rate |
| Z | Zero rated |
| E | Exempt |
| AE | Reverse charge |
| K | Intra-community supply |
| G | Export outside EU |
| O | Outside scope of VAT |
| L | Canary Islands |
| M | Ceuta and Melilla |

## Note Fields

```php
$invoice = (new Invoice())
    ->setId('INV-2024-001')
    ->setNote('Payment within 30 days. Thank you for your business.');
```

## Currency

```php
$invoice = (new Invoice())
    ->setId('INV-2024-001')
    ->setDocumentCurrencyCode('EUR');
```

### Tax Currency

For invoices with tax in a different currency:

```php
$invoice->setTaxCurrencyCode('USD');
```

## Rounding

```php
$legalMonetaryTotal = (new LegalMonetaryTotal())
    ->setLineExtensionAmount(999.99)
    ->setTaxExclusiveAmount(999.99)
    ->setTaxInclusiveAmount(1209.99)
    ->setPayableRoundingAmount(0.01)  // Round up 1 cent
    ->setPayableAmount(1210.00);
```

## Generating the XML

```php
use NumNum\UBL\Generator;

$generator = new Generator();

// Generate invoice XML
$invoiceXml = $generator->invoice($invoice);

// Generate credit note XML
$creditNoteXml = $generator->creditNote($creditNote);

// Save to file
file_put_contents('invoice.xml', $invoiceXml);
```

## XML Validation

Validate generated XML against UBL schema:

```php
$generator = new Generator();
$xml = $generator->invoice($invoice);

$dom = new \DOMDocument();
$dom->loadXML($xml);

$schemaUrl = 'http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd';

if ($dom->schemaValidate($schemaUrl)) {
    echo "Valid UBL document\n";
} else {
    echo "Invalid UBL document\n";
}
```

## Unit Codes

Common unit codes from UN/ECE Recommendation 20:

```php
use NumNum\UBL\UnitCode;

UnitCode::UNIT;   // C62 - Unit
UnitCode::PIECE;  // H87 - Piece
UnitCode::HOUR;   // HUR - Hour
UnitCode::DAY;    // DAY - Day
UnitCode::WEEK;   // WEE - Week
UnitCode::MONTH;  // MON - Month
UnitCode::YEAR;   // ANN - Year
UnitCode::KILOGRAM;  // KGM - Kilogram
UnitCode::METER;     // MTR - Meter
UnitCode::LITER;     // LTR - Liter
```

## Next Steps

- [Creating Invoices](creating-invoices.md) - Basic invoice creation
- [Creating Credit Notes](creating-credit-notes.md) - Credit note creation
- [Reading UBL Files](reading-ubl-files.md) - Parse existing documents
- Check `tests/` folder for more code examples
