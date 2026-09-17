# Next release

## Added

- Add support for multiple billing references on invoices and credit notes while preserving the single-reference API

## Fixed

- Fix TypeError: Change setter types to nullable in reference classes to handle empty XML elements gracefully during parsing
  - `OrderReference::setId()` now accepts `?string`
  - `ProjectReference::setId()` now accepts `?string`
  - `ContractDocumentReference::setId()` now accepts `?string`
  - `InvoiceDocumentReference::setOriginalInvoiceId()` now accepts `?string`

### Maintenance

- Update dependency constraints to support Doctrine Collections 3.x
