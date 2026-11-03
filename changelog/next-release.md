# Next release

## Fixed

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
