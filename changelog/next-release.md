# Changelog for version [next-release]

### New features & improvements

1. AdditionalDocumentReference  
   - Added `DocumentDescription` 
2. Attachment
   - Added `ExternalReference` (`URI`) as an alternative for an `EmbeddedDocumentBinaryObject`
3. ClassifiedTaxCategory
   - Fixed the appearing order of `Name` and `Percent`
4. Invoice
   - Added `ProfileID`
   - Added support for multiple `AdditionalDocumentReference` children
5. Item
   - Made `Description` optional
6. Party
   - Added `EndpointID`
   - Made `PartyName` optional

### Breaking changes

Changed `InvoiceLine`'s default `UnitCode` from `'MON'` to `UnitCode::UNIT` (in order to match `Price`'s default UnitCode).
