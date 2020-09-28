# Accept-Language HTTP Header Parser
A parser for the Accept-Language HTTP header following [RFC 2616](https://tools.ietf.org/html/rfc2616#section-14.4)

[![Build Status](https://travis-ci.org/Syonix/http-header-accept-language.svg?branch=master)](https://travis-ci.org/Syonix/http-header-accept-language)

## Usage
```php
use Syonix\Http\Header\AcceptLanguage\AcceptLanguage;

$parsed = AcceptLanguage::parse('da, en-gb;q=0.8, en;q=0.7'); // Returns Array ordered by quality (q)
$parsed = AcceptLanguage::match('de-CH, de-DE;q=0.9, de;q=0.8', ['de-DE', 'de']); // Returns 'de-DE'
```

## Notes
* `AcceptLanguage::match()` throws a RuntimeException, if none of the accepted languages matches and `*` is not in the `Accept-Languages` string.
* If `*` is accepted and none of the other accepted languages match, the passed `$default` parameter is passed
* If `$default` is omitted, the first element of the `$locale` array is returned.
* Both `parse` and `match` are case insensitive. 
* `parse` always returns the case of the input string
* `match` always returns the case of the provided locale array. 
* As per the RFC, the quality value defaults to `q=1`.
