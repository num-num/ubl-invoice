# Changelog for next release

### New features & improvements

- Add `DespatchDocumentReference` support to `Invoice`
  - New `DespatchDocumentReference` class with `id` property
  - Added `getDespatchDocumentReference()` / `setDespatchDocumentReference()` methods to `Invoice`
- Add FRCTC Electronic Address (0225) to EASCode - Thanks [@UbiManu](https://github.com/UbiManu)
- Add `<cac:AddressLine>` support to `<cac:Address>` for additional unstructured address lines - Thanks [@fMads](https://github.com/fMads)
  - New `AddressLine` class with `getLine()` / `setLine()` methods
  - `Address::getAddressLines()` - returns array of AddressLine objects
  - `Address::setAddressLines(array $addressLines)` - set all address lines
  - `Address::addAddressLine(AddressLine $addressLine)` - add a single address line
  - Supports multiple `<cac:AddressLine>` elements per address (UBL 2.1 compliant)

### Bug fixes

- Fix `Attachment::xmlSerialize()` to not output `EmbeddedDocumentBinaryObject` when only an `externalReference` is set - Thanks [@fMads](https://github.com/fMads)

