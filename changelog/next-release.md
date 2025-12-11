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
- Add `<cac:OriginCountry>` support to `<cac:Item>` for specifying country of origin - Thanks [@fMads](https://github.com/fMads)
- Update `AllowanceCharge::allowanceChargeReasonCode` to accept `int|string|null` instead of `?int`, allowing string values like "ZZZ" per UNCL7161 - Thanks [@Mikael-Leger](https://github.com/Mikael-Leger)

### Bug fixes

- Fix `Attachment::xmlSerialize()` to not output `EmbeddedDocumentBinaryObject` when only an `externalReference` is set - Thanks [@fMads](https://github.com/fMads)

### Maintenance

- Update dependency constraints to support Carbon 3.x and Doctrine Collections 2.x - Thanks [@GHuygen](https://github.com/GHuygen)

