## Matomo modifications to libs/

In general, bug fixes and improvements are reported upstream.  Until these are
included upstream, we maintain a list of bug fixes and local mods made to
third-party libraries:

 * PclZip/
   Source: https://github.com/chamilo/pclzip
   Tag: https://github.com/chamilo/pclzip/releases/tag/v2.8.5
   Fixes:
     - line 1720, added possibility to define a callable for `PCLZIP_CB_PRE_EXTRACT`. Before one needed to pass a function name (see [#5](https://github.com/matomo-org/component-decompress/pull/5/files#diff-3816f6393a3538d218dd07cd1be1cc787a84e5ab48554fee694e4958e7681930R1720))
     - line 1790, convert to integer to avoid warning on PHP 7.1+ (see [#9](https://github.com/matomo-org/component-decompress/pull/9))
     - line 3683, ignore touch() - utime failed warning (see [#5](https://github.com/matomo-org/component-decompress/pull/5))
     - line 5408, replaced `php_uname()` by `PHP_OS` (see [#2](https://github.com/matomo-org/component-decompress/issues/2))
