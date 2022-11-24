# Changelog for version [next-release]

### New features & improvements

- AdditionalDocumentReference
   - Added `DocumentDescription`
- Attachment
   - Added `ExternalReference` (`URI`) as an alternative for an `EmbeddedDocumentBinaryObject`
- ClassifiedTaxCategory
   - Fixed the appearing order of `Name` and `Percent`
- Invoice
   - Added `ProfileID`
   - Added support for multiple `AdditionalDocumentReference` children
- Item
   - Made `Description` optional
- Party
   - Added `EndpointID`
   - Made `PartyName` optional
- Added PHP 8 support
- Added Address->CountrySubentity()
- Fixed xmlSerialize() compatibility warnings
- Fixed number_format null values warning

### Breaking changes

Changed `InvoiceLine`'s default `UnitCode` from `'MON'` to `UnitCode::UNIT` (in order to match `Price`'s default UnitCode).
