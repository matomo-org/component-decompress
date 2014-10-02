# Piwik/Decompress

Library providing several adapters to decompress files.

Supports the following compression formats:

- Zip
- Gzip
- Tar (gzip or bzip)

With the following adapters:

- `PclZip`, based on the [PclZip library](http://www.phpconcept.net/pclzip/)
- `ZipArchive`, based on PHP's [Zip extension](http://fr.php.net/manual/en/book.zip.php)
- `Gzip`, based on PHP's native Gzip functions
- `Tar`, based on the [Archive_Tar library](https://github.com/pear/Archive_Tar) from PEAR
