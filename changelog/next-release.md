# Changelog for next release

### Bug fixes

- Fix `Attachment::xmlSerialize()` to not output `EmbeddedDocumentBinaryObject` when only an `externalReference` is set. Previously, the method always wrote the embedded document element even when there was no file content, causing invalid XML. Now the embedded document is only written when `filePath` or `base64Content` is provided.
