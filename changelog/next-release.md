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

### Maintenance

- Update dependency constraints to support Doctrine Collections 3.x
