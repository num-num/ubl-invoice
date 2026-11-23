# Next release

## Added

- Support `ext:UBLExtensions` (e.g. for Croatian fiscalization) when writing and reading documents
  - Add the new `Extension` class and `Invoice::addExtension()`, `setExtensions()` and `getExtensions()`
  - `Generator::invoice()`, `creditNote()` and `debitNote()` take an optional `$additionalNamespaces` argument (`[uri => prefix]`) for namespaces used inside the extension content

- Support multiple document-level notes (`cbc:Note`, BT-22) through `addNote()`, `setNotes()` and `getNotes()`, e.g. for the French CIUS
  - `setNote()` and `getNote()` keep working with a single note; `getNote()` returns the first one

- Support multiple billing references when writing and reading documents through `setBillingReferences()`, `addBillingReference()` and `getBillingReferences()`
  - Keep `setBillingReference()` and `getBillingReference()` compatible with a single reference
  - Normalize collection keys so `getBillingReference()` also returns the first reference after XML parsing

## Fixed

- Fix automatic decimal detection in `NumberFormatter` when no `$decimals` argument is given (#164)
  - On PHP 8 with a decimal-comma locale (e.g. `de_DE`), all decimals were dropped, because float-to-string conversion no longer uses the locale's decimal separator
  - Small values represented in scientific notation (e.g. `1.0E-5`) now keep their decimals
  - Decimal-comma locales on PHP 7.4 keep working

- Fix document reference elements being written in the wrong order, producing XML that fails UBL schema validation
  - UBL declares these elements in an `xsd:sequence`, and the required order differs per document type
  - On an `Invoice`, `cac:ContractDocumentReference` was written before `cac:DespatchDocumentReference`
    and `cac:OriginatorDocumentReference` after `cac:AdditionalDocumentReference`
  - A `CreditNote` and a `DebitNote` inherit the same serializer but require a different order again
  - Any document combining a contract reference with a despatch, receipt or additional reference was affected

- Fix TypeError: Change setter types to nullable in reference classes to handle empty XML elements gracefully during parsing
  - `OrderReference::setId()` now accepts `?string`
  - `ProjectReference::setId()` now accepts `?string`
  - `ContractDocumentReference::setId()` now accepts `?string`
  - `InvoiceDocumentReference::setOriginalInvoiceId()` now accepts `?string`

- Fix `cac:PaymentMandate` writing a `cac:PayeeFinancialAccount` child, which does not exist in UBL 2.1 and produced schema-invalid XML
  - `PaymentMandate` now supports `cac:PayerFinancialAccount` (EN 16931 BT-91, the debited account for SEPA direct debit) through the new `PayerFinancialAccount` class
  - `cbc:ID` is only written when it is set
  - **Breaking:** `PaymentMandate::getPayeeFinancialAccount()`, `PaymentMandate::setPayeeFinancialAccount()` and the public `PaymentMandate::$xmlTagName` property are removed; use `getPayerFinancialAccount()` / `setPayerFinancialAccount()` instead

### Maintenance

- Update dependency constraints to support Doctrine Collections 3.x
