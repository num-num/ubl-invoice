# Changelog for next release

### New features & improvements

- Add `<cac:AddressLine>` support to `<cac:Address>` for additional unstructured address lines
  - New `AddressLine` class with `getLine()` / `setLine()` methods
  - `Address::getAddressLines()` - returns array of AddressLine objects
  - `Address::setAddressLines(array $addressLines)` - set all address lines
  - `Address::addAddressLine(AddressLine $addressLine)` - add a single address line
  - Supports multiple `<cac:AddressLine>` elements per address (UBL 2.1 compliant)

