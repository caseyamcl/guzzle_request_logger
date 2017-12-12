# guzzle_request_logger

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A simple request logging middleware 

## Structure

```
src/   - Source Files
tests/ - Unit Tests
```


## Install

Via Composer

``` bash
$ composer require caseyamcl/guzzle_request_logger
```

## Usage

``` php
// $logger is any PSR-6-compatible logging class (e.g. Monolog, Symfony Logger, etc)

$stack \GuzzleHttp\HandlerStack::create();
$stack->push(new RequestLogger($logger));

$client = new \GuzzleHttp\Client(['handler' => $stack]);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email caseyamcl@gmail.com instead of using the issue tracker.

## Credits

- [Casey McLaughlin][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/caseyamcl/guzzle_request_logger.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/caseyamcl/guzzle_request_logger/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/caseyamcl/guzzle_request_logger.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/caseyamcl/guzzle_request_logger.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/caseyamcl/guzzle_request_logger.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/caseyamcl/guzzle_request_logger
[link-travis]: https://travis-ci.org/caseyamcl/guzzle_request_logger
[link-scrutinizer]: https://scrutinizer-ci.com/g/caseyamcl/guzzle_request_logger/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/caseyamcl/guzzle_request_logger
[link-downloads]: https://packagist.org/packages/caseyamcl/guzzle_request_logger
[link-author]: https://github.com/caseyamcl
[link-contributors]: ../../contributors
