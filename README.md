#Germania\Cookie

Callable wrapper around **setcookie()** and **filter\_input( INPUT_COOKIE )**


##Installation

```bash
$ composer require germania-kg/cookie
```


##Getting cookies

```php
<?php
use Germania\Cookie\CookieGetter;

// Optionally:
// have your PSR-3 Logger at hand
// or set filter type:
$getter = new CookieGetter( INPUT_COOKIE);
$getter = new CookieGetter( INPUT_COOKIE, $log);
$getter = new CookieGetter( INPUT_COOKIE, $log, \FILTER_SANITIZE_STRING);
$value = $getter( 'foo' );
```


##Setting cookies

```php
<?php
use Germania\Cookie\CookieSetter;

// Optional/Defaults
$defaults = [
    'path'     => '',
    'domain'   => '',
    'secure'   => false,
    'httponly' => false
];

// Optionally, have your PSR-3 Logger at hand
$setter = new CookieSetter( $defaults );
$setter = new CookieSetter( $defaults, $log);
$boolean = $setter( 'foo', 'bar', time()+3600 );
```




##Development and Testing

Develop using `develop` branch, using [Git Flow](https://github.com/nvie/gitflow).   
**Currently, no tests are specified.**

```bash
$ git clone git@github.com:GermaniaKG/Downloads.git germania-downloads
$ cd germania-downloads
$ cp phpunit.xml.dist phpunit.xml
$ phpunit
```
