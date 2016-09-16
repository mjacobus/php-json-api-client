Brofist Json Client
================

Simple wrapper for Guzzle.

Code information:

[![Build Status](https://travis-ci.org/mjacobus/php-json-api-client.png?branch=master)](https://travis-ci.org/mjacobus/php-json-api-client)
[![Coverage Status](https://coveralls.io/repos/mjacobus/php-json-api-client/badge.png)](https://coveralls.io/r/mjacobus/php-json-api-client)
[![Code Climate](https://codeclimate.com/github/mjacobus/php-json-api-client.png)](https://codeclimate.com/github/mjacobus/php-json-api-client)
[![StyleCI](https://styleci.io/repos/68288559/shield)](https://styleci.io/repos/68288559)

Scrutinizer

[![Code Coverage](https://scrutinizer-ci.com/g/mjacobus/php-json-api-client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mjacobus/php-json-api-client/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mjacobus/php-json-api-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mjacobus/php-json-api-client/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/mjacobus/php-json-api-client/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mjacobus/php-json-api-client/build-status/master)

Package information:

[![Latest Stable Version](https://poser.pugx.org/brofist/json-api-client/v/stable.svg)](https://packagist.org/packages/brofist/json-api-client)
[![Total Downloads](https://poser.pugx.org/brofist/json-api-client/downloads.svg)](https://packagist.org/packages/brofist/json-api-client)
[![Latest Unstable Version](https://poser.pugx.org/brofist/json-api-client/v/unstable.svg)](https://packagist.org/packages/brofist/json-api-client)
[![License](https://poser.pugx.org/brofist/json-api-client/license.svg)](https://packagist.org/packages/brofist/json-api-client)
[![Dependency Status](https://gemnasium.com/badges/github.com/mjacobus/php-json-api-client.svg)](https://gemnasium.com/github.com/mjacobus/php-json-api-client)



## Installing

### Installing via Composer

Append the lib to your requirements key in your composer.json.

```bash
composer require brofist/json-api-client
```

## Usage

```php
use Brofist\ApiClient\Json;

$client = new Json([
    'endpoint' => 'http://foo.bar/v1/blah',
    // optional
    'basicAuth' => ['username', 'password'],
]);

$resources = $client->get('/resources', [
    'limit' => 100,
]);

$resources = $client->put('/resources/1', [
    'name' => 'foo',
]);

$resources = $client->post('/resources', [
    'name' => 'foo',
]);
```


## Issues/Features proposals

[Here](https://github.com/mjacobus/php-json-api-client/issues) is the issue tracker.

## Contributing

Only tested code will be accepted. Please follow fix the style guide.

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

### How to run the tests:

```bash
./vendor/bin/phpunit
```

### To check the code standard run:

```bash
# Fixes code
./vendor/bin/broc-code fix src
./vendor/bin/broc-code fix tests
```

## Lincense

This software is distributed under the [MIT](MIT-LICENSE) license.

## Authors

- [Marcelo Jacobus](https://github.com/mjacobus)
