# arionum-cli

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Style CI][ico-styleci]][link-styleci]
[![Code Coverage][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A PHP-based command line wallet for Arionum.

## Install

Via Composer

```bash
$ composer global require pxgamer/arionum-cli
```

Via Phive

```bash
$ phive install pxgamer/arionum-cli
```

## Usage

```bash
arionum [command]
```

### Available Commands

Print the balance:

```bash
arionum balance
```

Print the wallet's data:

```bash
arionum export
```

Display data about the current block:

```bash
arionum block
```

Encrypt the wallet:

```bash
arionum encrypt
```

Decrypt the wallet:

```bash
arionum decrypt
```

Display the latest transactions:

```bash
arionum transactions
```

Display data about a specific transaction:

```bash
arionum transaction [id]
```

Send a transaction (with an optional message):

```bash
arionum send [address] [value] [message]
```

## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please email owzie123@gmail.com instead of using the issue tracker.

## Credits

- [pxgamer][link-author]
- [arionum][link-arionum]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

Original code by [@arionum][link-arionum], view the [original license](LICENSE_ORIGINAL).

[ico-version]: https://img.shields.io/packagist/v/pxgamer/arionum-cli.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/pxgamer/arionum-cli/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/122668084/shield
[ico-code-quality]: https://img.shields.io/codecov/c/github/pxgamer/arionum-cli.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pxgamer/arionum-cli.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/pxgamer/arionum-cli
[link-travis]: https://travis-ci.org/pxgamer/arionum-cli
[link-styleci]: https://styleci.io/repos/122668084
[link-code-quality]: https://codecov.io/gh/pxgamer/arionum-cli
[link-downloads]: https://packagist.org/packages/pxgamer/arionum-cli
[link-author]: https://github.com/pxgamer
[link-arionum]: https://github.com/arionum
[link-contributors]: ../../contributors
