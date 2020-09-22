# Changelog for version [next-release]

### New features & improvements

- Added return type declarations in every class
- Added PartyTaxScheme
- Added Party->setPartyTaxScheme()

### Breaking changes

- Party->setTaxCompanyName(string) has been removed since PartyTaxScheme has been added
- Party->setTaxCompanyId(string) has been removed since PartyTaxScheme has been added
- Party->setTaxScheme(string) has been removed since PartyTaxScheme has been added
