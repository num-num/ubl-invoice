# Changelog for v2.0.0-beta

### New features & improvements

- Possibility to deserialize XML into object tree
- Add `<cac:DespatchDocumentReference>` to `<Invoice>` - Thanks [@fMads](https://github.com/fMads)
- Add `<cac:ReceiptDocumentReference>` to `<Invoice>`
- Add `<cac:OriginatorDocumentReference>` to `<Invoice>`

### Breaking changes ⚠️

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

# Changelog for v1.21.4

### New features & improvements

- Add missing class PaymentMandate. See Also https://docs.peppol.eu/poacc/billing/3.0/syntax/ubl-invoice/cac-PaymentMeans/cac-PaymentMandate/
- Add <cac:PaymentMandate> to <PaymentMeans>

#### Bug fixes

- <cbc:MultiplierFactorNumeric> in AllowanceCharge was incorrectly set to baseAmount instead of the actual multiplierFactorNumeric - Thanks [@dragonfly4](https://github.com/dragonfly4)
- Fix argument and return type annotation for `Invoice::setBillingReference()` - Thanks [@ronaldsgailis](https://github.com/ronaldsgailis)
- Add PaymentMandate class and update PaymentMeans class - Thanks [@UbiManu](https://github.com/UbiManu)

# Changelog for v1.21.3

#### Bug fixes

- Don't round `<cac:Price> <cbc:PriceAmount>` to two decimals

# Changelog for v1.21.2

#### Bug fixes

- <cbc:MultiplierFactorNumeric> in AllowanceCharge should not crash when the system uses localized number output
- documentTypeCode in AdditionalDocumentReference should not be limited to int values - Thanks [@tgeorgel](https://github.com/tgeorgel)

# Changelog for v1.21.1

#### Bug fixes

- <cac:Price> <cbc:PriceAmount> should not be rounded to zero decimals

# Changelog for v1.21.0

### New features & improvements

- Add missing ICD Code list. See also https://docs.peppol.eu/poacc/billing/3.0/codelist/ICD/
- Make <cac:ClassifiedTaxCategory> <cbc:Percent> optional - Thanks [@chipco](https://github.com/chipco)
- Add <cac:AccountingContact> to <Invoice> - Thanks [@tgeorgel](https://github.com/tgeorgel)
- Add <cbc:ID> to <cac:Contact> - Thanks [@tgeorgel](https://github.com/tgeorgel)
- Allow both <cbc:DocumentTypeCode> and <cbc:DocumentType> to be present in <cac:AdditionalDocumentReference> - Thanks [@tgeorgel](https://github.com/tgeorgel)

# Changelog for v1.20.0

### New features & improvements

- General change: don't round numbers to two decimals, always use the amount of provided decimals

# Changelog for v1.19.1

### New features & improvements

- Add `<cbc:PayableRoundingAmount />` to `<cac:LegalMonetaryTotal>` - Thanks [@ronaldsgailis](https://github.com/ronaldsgailis)

# Changelog for v1.19.0

### New features & improvements

- Add `<cac:CommodityClassification />` to `<cac:Item>`
-
# Changelog for v1.18.2

### New features & improvements

- Add `<cac:ProjectReference />` to `<Invoice>`

#### Bug fixes

- Fix Amount, BaseAmount, TaxCategory tag sort in `<cac:AllowanceCharge />`
- Fix parameter type for `<cac:AllowanceCharge>` on `<cac:InvoiceLine>` to array

# Changelog for v1.18.1

### Bug fixes

- Fix Creditnote `<cac:BillingReference>` validation issues, this tag should be optional

# Changelog for 1.18.0

### New features & improvements

- Support for `<cav:Item>` `<cac:StandardItemIdentification>` --> `cbc:IDPeppol` Scheme identifier - Thanks [@dragonfly4](https://github.com/dragonfly4)
- Support to add `<cac:Attachment>` content without having to use an external file ref but by adding it as a fileStream/fileContents - Thanks [@dietercoopman](https://github.com/dietercoopman), [@bagulho](https://github.com/bagulho)
- Add `<cbc:CompanyLegalForm>` to `<cac:PartyLegalEntity>` - Thanks [@vsadrn](https://github.com/vsadrn)
- Improved `UNCL4461` support for `<cac:PaymentMeans>` - Thanks [@TSimkus](https://github.com/TSimkus), [@TecsiAron](https://github.com/TecsiAron)
- Add `<cac:OrderLineReference>` support for `<cac:InvoiceLine>` - Thanks [@TSimkus](https://github.com/TSimkus)
- Add `<cac:AllowanceCharge>` to `<cac:InvoiceLine>` - Thanks [@TSimkus](https://github.com/TSimkus)
- Add support for `<cac:BillingReference>` in Creditnotes - Thanks [@UlusoftConsultancy](https://github.com/UlusoftConsultancy)
- Add `<cbc:ChargeTotalAmount>` to `<cac:LegalMonetaryTotal>` - Thanks [@Quazz](https://github.com/Quazz)
- Add support for TaxExemptionReason and TaxExemptionReasonCode to `<cac:ClassifiedTaxCategory>` - Thanks [@bagulho](https://github.com/bagulho)

### Breaking changes

- Add support to include multiple `PaymentMeans` - Thanks [@Quazz](https://github.com/Quazz)

### Bug fixes

- Fix `InvoiceTypeCode` documentation dead link - Thanks [@TSimkus](https://github.com/TSimkus)
- Changed `<cbc:MultiplierFactorNumeric />` type from `int` to `float` - Thanks [@TSimkus](https://github.com/TSimkus)

# Changelog for version 1.17.0

### New features & improvements

- Add list of `VatExemptionCode` options

# Changelog for version 1.16.0

### New features & improvements

- Add `<cac:StandardItemIdentification>` to `<cac:Item>`
- Add `<cac:PayeeParty>` to `<Invoice>`

# Changelog for version 1.15.5

### New features & improvements

- Don't output an InvoiceLine `<cac:TaxScheme />` when no `<cac:Price />` was set
- Don't output `<cac:Item>` under InvoiceLine unless explicitely set

# Changelog for version v1.15.4

### New features & improvements

- Added UNCL5305 codes to be used in various implementations
- Improved default result for UNCL5305 code in `TaxCategory->getId()` if `TaxCategory->setId()` was not used

# Changelog for version v1.15.3

### New features & improvements

- Added more EAS codes to be used in various implementations

# Changelog for version v1.15.2

### New features & improvements

- Added `EASCode` list with additional EAS codes to be used in various implementations

# Changelog for version v1.15.1

### New features & improvements

- Add `PrepaidAmount` to `LegalMonetaryTotal`
- Add `IssueDate` to `OrderReference`
- Add `ICDCode` list to be used in various other places
- Fixed LegalMonetaryTotal which contained a syntax error

# Changelog for version v1.15

### New features & improvements

- Added the possibility to use `AdditionalDocumentReference` without `Attachment` - Thanks [@pjcarly](https://github.com/pjcarly)
- Added `DocumentTypeCode` on `AdditionalDocumentReference`
- Added setter/getter `InvoicePeriod` `DescriptionCode` (VAT date code UNCL2005 subset) - Thanks [@markovic131](https://github.com/markovic131)
- Added `PartyIdentificationSchemeName` to `Party` - Thanks [@tgeorgel](https://github.com/tgeorgel)
- Added deprecation warning for setAdditionalDocumentReference
- Fixed `ContractDocumentReference` XML position - Thanks [@markovic131](https://github.com/markovic131)
- Fixed `Name` position in `TaxScheme` - Thanks [@tgeorgel](https://github.com/tgeorgel)
- Fixed `InvoicePeriod`, `OrderReference`, `ContractDocumentReference` sorting
- Fixed DocumentType which is not a CommonAggregateComponent - Thanks [@christopheg](https://github.com/christopheg)

# Changelog for version 1.14

### New features & improvements

- Add support for the `CreditNote` root tag and `CreditNoteLine` tags - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)
- Added `DocumentDescription` in `AdditionalDocumentReference` - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)
- Added `ExternalReference` (`URI`) as an alternative for an `EmbeddedDocumentBinaryObject` in `Attachment` - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)
- Fixed the appearing order of `Name` and `Percent` in `ClassifiedTaxCategory` - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)
- Added `ProfileID` to `Invoice` - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)
- Added support for multiple `AdditionalDocumentReference` children to `Invoice` - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)
- Made `Description` optional in `Item` - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)
- Added `EndpointID` to `Party` - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)
- Made `PartyName` optional in `Party` - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)
- Added PHP 8 support in `composer.json` - Thanks [@antal-levente](https://github.com/antal-levente)
- Added `Address->CountrySubentity()` - Thanks [@antal-levente](https://github.com/antal-levente)
- Fixed `xmlSerialize()` compatibility warnings - Thanks [@antal-levente](https://github.com/antal-levente)
- Fixed number_format null values warning - Thanks [@antal-levente](https://github.com/antal-levente)
- Add support for `cac:PartyIdentification` `schemeId` attribute
- Add support for `cac:AllowanceCharge` in `Price` tag

### Breaking changes

- Changed `InvoiceLine`'s default `UnitCode` from `'MON'` to `UnitCode::UNIT` (in order to match `Price`'s default UnitCode) - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)

### Breaking changes

- Changed `InvoiceLine`'s default `UnitCode` from `'MON'` to `UnitCode::UNIT` (in order to match `Price`'s default UnitCode) - Thanks [@JorisDebonnet](https://github.com/JorisDebonnet)

# Changelog for version 1.13

### New features & improvements

- Add `<cac:AccountingCost>` child node for `<cac:InvoiceLine>`

# Changelog for version 1.12

### New features & improvements

- PHP8 Support — Thanks [@ChristianVermeulen](https://github.com/ChristianVermeulen)

# Changelog for version 1.11

### New features & improvements

- Added new ContractDocumentReference class & tag `<cac:ContractDocumentReference />` to Invoice — Thanks [@mabjavaid](https://github.com/mabjavaid)
- Remove duplicate validation for `id` on invoice — Thanks [@mabjavaid](https://github.com/mabjavaid)
- Added new cac:PartyIdentification tag to cac:Party

# Changelog for version 1.10.2

### New features & improvements

- Added AdditionalStreetName in Address.php — Thanks [@jbputit](https://github.com/jbputit)

# Changelog for version 1.10.1

### New features & improvements

- Added year, month and piece as additional units in UnitCode.php — Thanks [@jbputit](https://github.com/jbputit)
- Added possibility to set InvoicePeriod in InvoiceLine — Thanks [@jbputit](https://github.com/jbputit)

# Changelog for version 1.10

### New features & improvements

- Added new OrderReferen class & tag `<cac:OrderReference>` to Invoice — Thanks [@jbputit](https://github.com/jbputit)

# Changelog for version 1.9.6

### New features & improvements

- Support for `<cbc:SupplierAssignedAccountID>` in `<cac:AccountingCustomerParty>` — Thanks [@eborned](https://github.com/eborned)

# Changelog for version 1.9.5

### New features & improvements

- Add `<cac:BuyersItemIdentification>` child node for `<cac:Item>` — Thanks [@eborned](https://github.com/eborned)

# Changelog for version 1.9.4

### New features & improvements

- Bugfix: Fix order of `<cbc:DueDate>` node in `<Invoice>`

# Changelog for version 1.9.3

### New features & improvements

- Bugfix: Use correct number formatting when `<cbc:InvoicedQuantity>` in `<cac:InvoiceLine>` contains a float value
- Bugfix: Use correct number formatting when `<cbc:BaseQuantity>` in `<cac:Price>` contains a float value
- Bugfix: Use correct number formatting when `<cbc:PriceAmount>` in `<cac:Price>` contains a float value

# Changelog for version 1.9.2

### New features & improvements

- Add `<cbc:InstructionNote>` to `<cac:PaymentMeans>` for non structured payment instructions

# Changelog for version 1.9.1

### New features & improvements

- Bugfix in `<cac:Party>`, set correct order for child nodes `<cac:PartyLegalEntity>` & `<cac:Contact>` — Thanks [@stedekay](https://github.com/stedekay)

# Changelog for version 1.9

### New features & improvements

- Added return type declarations in every class
- Added `PartyTaxScheme`
- Added `Party->setPartyTaxScheme()`

### Breaking changes

- `Party->setTaxCompanyName(string)` has been removed since `PartyTaxScheme` has been added
- `Party->setTaxCompanyId(string)` has been removed since `PartyTaxScheme` has been added
- `Party->setTaxScheme(string)` has been removed since `PartyTaxScheme` has been added
