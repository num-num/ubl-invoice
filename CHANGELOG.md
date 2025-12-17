# Changelog for v2.0.0-beta

### New features & improvements

- Possibility to deserialize XML into object tree
- Add `xmlDeserialize` support for `CreditNote` class to properly parse `<cac:CreditNoteLine>` elements - Thanks [@RoanB](https://github.com/RoanB)
- Add `<cac:DespatchDocumentReference>` to `<Invoice>` - Thanks [@fMads](https://github.com/fMads)
- Add `<cac:ReceiptDocumentReference>` to `<Invoice>`
- Add `<cac:OriginatorDocumentReference>` to `<Invoice>`
- Add FRCTC Electronic Address (0225) to EASCode - Thanks [@UbiManu](https://github.com/UbiManu)
- Add `<cac:AddressLine>` support to `<cac:Address>` for additional unstructured address lines - Thanks [@fMads](https://github.com/fMads)
- Add `<cac:OriginCountry>` support to `<cac:Item>` for specifying country of origin - Thanks [@fMads](https://github.com/fMads)
- Update `AllowanceCharge::allowanceChargeReasonCode` to accept `int|string|null` instead of `?int`, allowing string values like "ZZZ" per UNCL7161 - Thanks [@Mikael-Leger](https://github.com/Mikael-Leger)

### Bug fixes

- Fix `Attachment::xmlSerialize()` to not output `EmbeddedDocumentBinaryObject` when only `externalReference` is set - Thanks [@fMads](https://github.com/fMads)

### Maintenance

- Update dependency constraints to support Carbon 3.x and Doctrine Collections 2.x - Thanks [@GHuygen](https://github.com/GHuygen)

### Breaking changes

- `Invoice::setAccountingSupplierParty` now requires an `AccountingParty` instead of a `Party` object
- `Invoice::setAccountingCustomerParty` now requires an `AccountingParty` instead of a `Party` object
- `Invoice::getAccountingSupplierParty` now returns an `AccountingParty` instead of a `Party` object
- `Invoice::getAccountingCustomerParty` now returns an `AccountingParty` instead of a `Party` object
- `Invoice::accountingCustomerPartyContact` has been removed, use `Invoice::getAccountingCustomerParty()->setAccountingContact()` instead
- `Invoice::setSupplierAssignedAccountID`/`Invoice::getSupplierAssignedAccountID` no longer exists and has moved to `AccountingParty::setSupplierAssignedAccountID`/`AccountingParty::getSupplierAssignedAccountID`
- `Attachment::setFileStream` / `Attachment::getFileStream` to manually set the Attachment xml tag base 64 string content, has been renamed to `Attachment::setBase64Content` / `Attachment::getBase64Content`
- Functions `setUBLVersionID`/`getUBLVersionID` have been renamed to `setUBLVersionId`/`getUBLVersionId`
- Functions `setSupplierAssignedAccountID`/`getSupplierAssignedAccountID` have been renamed to `setSupplierAssignedAccountId`/`getSupplierAssignedAccountId`
- Functions `setUnitCodeListID`/`getUnitCodeListID` have been renamed to `setUnitCodeListId`/`getUnitCodeListId`
- Functions `setSchemeID`/`getSchemeID` have been renamed to `setSchemeId`/`getSchemeId`
- Functions `setCustomizationID`/`getCustomizationID` have been renamed to `setCustomizationId`/`getCustomizationId`
- Functions `setProfileID`/`getProfileID` have been renamed to `setProfileId`/`getProfileId`
