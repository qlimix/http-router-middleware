# Http router middleware

[![Travis CI](https://api.travis-ci.org/qlimix/http-router-middleware.svg?branch=master)](https://travis-ci.org/qlimix/http-router-middleware)
[![Coveralls](https://img.shields.io/coveralls/github/qlimix/http-router-middleware.svg)](https://coveralls.io/github/qlimix/http-router-middleware)
[![Packagist](https://img.shields.io/packagist/v/qlimix/http-router-middleware.svg)](https://packagist.org/packages/qlimix/http-router-middleware)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/qlimix/http-router-middleware/blob/master/LICENSE)

## Install

Using Composer:

~~~
$ composer require qlimix/http-router-middleware
~~~

## usage

```php
<?php

use Qlimix\Http\Middleware\RouterMiddleware;

$router = new Router();
$locator = new Locator();

$middleware = new RouterMiddleware($router, $locator);
```

## Testing
To run all unit tests locally with PHPUnit:

~~~
$ vendor/bin/phpunit
~~~

## Quality
To ensure code quality run grumphp which will run all tools:

~~~
$ vendor/bin/grumphp run
~~~

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
