# Matomo/Decompress

Component providing several adapters to decompress files.

[![Build Status](https://travis-ci.org/matomo-org/component-decompress.svg?branch=master)](https://travis-ci.org/matomo-org/component-decompress)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/matomo-org/component-decompress/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/matomo-org/component-decompress/?branch=master)

It supports the following compression formats:

- Zip
- Gzip
- Tar (gzip or bzip)

With the following adapters:

- `PclZip`, based on the [PclZip library](http://www.phpconcept.net/pclzip/)
- `ZipArchive`, based on PHP's [Zip extension](http://fr.php.net/manual/en/book.zip.php)
- `Gzip`, based on PHP's native Gzip functions
- `Tar`, based on the [Archive_Tar library](https://github.com/pear/Archive_Tar) from PEAR

## Installation

With Composer:

```json
{
    "require": {
        "matomo/decompress": "*"
    }
}
```

## Usage

All adapters have the same API as they implement `Matomo\Decompress\DecompressInterface`:

```php
$extractor = new \Matomo\Decompress\Gzip('file.gz');

$extractedFiles = $extractor->extract('some/directory');

if ($extractedFiles === 0) {
    echo $extractor->errorInfo();
}
```

## License

The Decompress component is released under the [LGPL v3.0](http://choosealicense.com/licenses/lgpl-3.0/).
