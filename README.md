# Germania KG · Cookie

Callable wrapper around **setcookie()** and **filter\_input( INPUT_COOKIE )**

[![Build Status](https://travis-ci.org/GermaniaKG/Cookie.svg?branch=master)](https://travis-ci.org/GermaniaKG/Cookie)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/Cookie/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Cookie/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/Cookie/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Cookie/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/GermaniaKG/Cookie/badges/build.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Cookie/build-status/master)

## Installation

```bash
$ composer require germania-kg/cookie
```


## Getting cookies

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


## Setting cookies

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

## Pimple Service Provider

```php
<?php
use Germania\Cookie\Providers\PimpleServiceProvider;
use Psr\Log\LoggerInterface;


// have your Pimple DIC ready, and optionally a PSR3 Logger:
$sp = new PimpleServiceProvider;

$cookie_config = [
    "path" =>     "/path/to/...",
    "secure" =>   true,
    "httponly" => true
];
$sp = new PimpleServiceProvider( $cookie_config, $psr3_logger );

$sp->register( $dic );

// Grab your services;
// See also above examaples.
$setter = $dic['Cookie.Setter'];
$getter = $dic['Cookie.Getter'];
```


## Development and Testing

Develop using `develop` branch, using [Git Flow](https://github.com/nvie/gitflow).   
**Currently, no tests are specified.**

```bash
$ git clone git@github.com:GermaniaKG/Cookie.git germania-cookie
$ cd germania-cookie
$ cp phpunit.xml.dist phpunit.xml
$ phpunit
```
